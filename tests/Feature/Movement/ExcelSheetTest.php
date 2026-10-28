<?php

namespace Tests\Feature\Movement;

use App\Livewire\Sales\NewSale;
use App\Models\Customer\Customer;
use App\Models\Movement\Movement;
use App\Models\Movement\RateLog;
use App\Models\Pricing\PricingRule;
use App\Models\Pricing\TaxSetting;
use App\Models\Sales\Sale;
use App\Models\Stock\Item;
use App\Models\User;
use App\Services\PricingService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * The three columns of the client's "Price Calculation for Radharani" sheet (Sales tab), with its own numbers. Each ends on
 * the sheet's grand total: 60,000, 5,500 and 4,000. The sheet's discount is the last line, after the round-off.
 *   DB_CONNECTION=mysql DB_DATABASE=radharani_erp php artisan test --filter=ExcelSheetTest
 */
class ExcelSheetTest extends TestCase
{
    use DatabaseTransactions;

    private User $user;
    private PricingService $pricing;

    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') === 'sqlite') {
            $this->markTestSkipped('Needs the MySQL database; see the note at the top of this class.');
        }
        $this->user = User::role('owner')->first() ?? $this->markTestSkipped('Needs the seeded owner user.');
        $this->actingAs($this->user);
        $this->pricing = app(PricingService::class);

        TaxSetting::current()->update(['jewellery_percent' => 3, 'hallmark_percent' => 18]);
        PricingRule::whereIn('kind', ['making', 'additional', 'discount', 'hallmark'])->update(['active' => false]);
        PricingRule::create(['kind' => 'hallmark', 'scope' => 'all', 'calc' => 'per_piece', 'value' => 75, 'active' => true, 'created_by' => $this->user->id]);
        $this->pricing->forgetRules();
    }

    private function piece(string $metal, string $purity, float $weight, string $makingType, float $making, bool $huid): Item
    {
        $item = Item::create(['packet_id' => $this->packetId(), 'internal_code' => 'XS' . random_int(100, 999), 'huid_code' => $huid ? 'X' . random_int(10000, 99999) : null, 'metal' => $metal, 'category' => 'Sakha',
            'purity' => $purity, 'weight' => $weight, 'making_type' => $makingType, 'making_value' => $making, 'status' => 'in_stock']);
        Movement::create(['trackable_type' => 'item', 'trackable_id' => $item->id, 'movement_type' => 'vault_out', 'user_id' => $this->user->id]);

        return $item;
    }

    /** The bill the way New Sale makes it: pieces, extras, round-off, then the discount at the end. */
    private function bill(array $items, float $extras, float $discount): array
    {
        $b = collect($items)->map(fn ($i) => $this->pricing->breakdown($i));

        return $this->pricing->withFinalDiscount($this->pricing->billTotals($b, $extras, 'flat', 0, 3, 18), 'flat', $discount, 3);
    }

    public function test_column_one_making_as_a_percentage(): void
    {
        RateLog::create(['metal' => 'gold', 'purity' => '22K', 'rate' => 14400, 'source' => 'manual', 'updated_by' => $this->user->id]);
        $item = $this->piece('gold', '22K', 3.36, 'percentage', 20, true);   // Paat Sakha: 3.36 g, 20%, HUID 75

        $b = $this->pricing->breakdown($item);
        $this->assertEquals(48384.00, $b['metal_value']);        // C5 = 14,400 x 3.36
        $this->assertEquals(9676.80, $b['making']);             // C6 = C5 x 20%
        $this->assertEquals(75, $b['huid_charge']);             // C8

        $bill = $this->bill([$item], 250, 141);                  // additional 250 (C12); the discount 141 (C14)
        $this->assertEquals(1755.32, $bill['gst']);              // CGST + SGST: the sheet's 877.662 twice
        $this->assertEquals(877.66, $bill['cgst']);
        $this->assertEquals(60141.12, $bill['gross']);           // C11 + C12 = 59,891.124 + 250
        $this->assertEquals(-0.12, $bill['round_off']);          // C13
        $this->assertEquals(60141, $bill['rounded_gross']);
        $this->assertEquals(60000, $bill['total']);              // C15
    }

    public function test_column_two_making_per_piece_two_pieces(): void
    {
        RateLog::create(['metal' => 'silver', 'purity' => 'Default', 'rate' => 270, 'source' => 'manual', 'updated_by' => $this->user->id]);
        $one = $this->piece('silver', 'Default', 7.25, 'flat_per_piece', 700, false);   // Silver Pola, piece one
        $two = $this->piece('silver', 'Default', 7.29, 'flat_per_piece', 700, false);   // piece two

        $b = collect([$one, $two])->map(fn ($i) => $this->pricing->breakdown($i));
        $this->assertEquals(3925.80, round($b->sum('metal_value'), 2));   // H6 = (7.25 + 7.29) x 270
        $this->assertEquals(1400, $b->sum('making'));                     // H7 = 700 a piece x 2
        $this->assertEquals(0, $b->sum('huid_charge'));                   // H9: HUID not applicable

        $bill = $this->bill([$one, $two], 175.5, 161);
        $this->assertEquals(5661.07, $bill['gross']);                     // H12 + H13
        $this->assertEquals(-0.07, $bill['round_off']);                   // H14
        $this->assertEquals(5500, $bill['total']);                        // H16
    }

    public function test_column_three_making_per_gram(): void
    {
        RateLog::create(['metal' => 'silver', 'purity' => 'Default', 'rate' => 270, 'source' => 'manual', 'updated_by' => $this->user->id]);
        $item = $this->piece('silver', 'Default', 7.25, 'flat_per_gram', 245, false);

        $b = $this->pricing->breakdown($item);
        $this->assertEquals(1957.50, $b['metal_value']);                  // M6
        $this->assertEquals(1776.25, $b['making']);                       // M7 = 245 a gram x 7.25

        $bill = $this->bill([$item], 175.5, 21);
        $this->assertEquals(4021.26, $bill['gross']);                     // M12 + M13
        $this->assertEquals(-0.26, $bill['round_off']);                   // M14
        $this->assertEquals(4000, $bill['total']);                        // M16
    }

    public function test_column_one_through_the_real_sale_form(): void
    {
        RateLog::create(['metal' => 'gold', 'purity' => '22K', 'rate' => 14400, 'source' => 'manual', 'updated_by' => $this->user->id]);
        $item = $this->piece('gold', '22K', 3.36, 'percentage', 20, true);
        $customer = Customer::create(['name' => 'Sheet Customer', 'phone' => (string) random_int(6000000000, 9999999999)]);

        $c = Livewire::test(NewSale::class)->set('customerId', $customer->id)->call('addItem', $item->id)
            ->call('next')->set('extras', [['name' => 'Sakha', 'amount' => '250']])->set('finalValue', '141')->call('next')->call('next');
        $this->assertEquals(60000, $c->get('total'));
        $c->call('next')->call('submit')->assertHasNoErrors();

        $sale = Sale::latest('id')->first();
        $this->assertEquals(60000, (float) $sale->total);
        $this->assertEquals(-0.12, (float) $sale->round_off);
        $this->assertEquals(141, (float) $sale->final_discount);
    }
}

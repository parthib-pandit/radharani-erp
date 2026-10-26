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
 * 25 October: a sale can price a piece at another weight (the piece itself never changes), staff can bargain
 * the making rate down per piece (never under 10% of it), and the bargain discount comes after the GST and the
 * round-off. Gold is 1,000 a gram, making 18% of the metal value, GST 3% (18% on the 100 hallmarking charge).
 *   DB_CONNECTION=mysql DB_DATABASE=radharani_erp php artisan test --filter=SaleBargainTest
 */
class SaleBargainTest extends TestCase
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
        $this->pricing->forgetRules();

        TaxSetting::current()->update(['jewellery_percent' => 3, 'hallmark_percent' => 18]);
        RateLog::create(['metal' => 'gold', 'purity' => '9K', 'rate' => 1000, 'source' => 'manual', 'updated_by' => $this->user->id]);
        PricingRule::whereIn('kind', ['making', 'additional', 'discount', 'hallmark'])->update(['active' => false]);
        PricingRule::create(['kind' => 'hallmark', 'scope' => 'all', 'calc' => 'per_piece', 'value' => 100, 'active' => true, 'created_by' => $this->user->id]);
        PricingRule::create(['kind' => 'making', 'scope' => 'all', 'calc' => 'percentage', 'value' => 18, 'active' => true, 'created_by' => $this->user->id]);
        $this->pricing->forgetRules();
    }

    private function piece(float $weight = 10): Item
    {
        $item = Item::create(['packet_id' => $this->packetId(), 'internal_code' => 'BG' . random_int(100, 999), 'huid_code' => 'H' . random_int(10000, 99999), 'metal' => 'gold', 'category' => 'Ring',
            'purity' => '9K', 'making_value' => 0, 'weight' => $weight, 'status' => 'in_stock']);
        // Out of the vault, so a sale can take it.
        Movement::create(['trackable_type' => 'item', 'trackable_id' => $item->id, 'movement_type' => 'vault_out', 'user_id' => $this->user->id]);

        return $item;
    }

    private function sale(Item $item)
    {
        $customer = Customer::create(['name' => 'Bargain Customer', 'phone' => (string) random_int(6000000000, 9999999999)]);

        return Livewire::test(NewSale::class)->set('customerId', $customer->id)->call('next')->call('addItem', $item->id);
    }

    public function test_a_weight_and_a_making_rate_can_be_bargained_in_the_service(): void
    {
        $item = $this->piece(10);

        $plain = $this->pricing->breakdown($item);
        $this->assertEquals(10000, $plain['metal_value']);
        $this->assertEquals(1800, $plain['making']);
        $this->assertSame('percentage', $plain['making_kind']);
        $this->assertEquals(18, $plain['making_default_rate']);

        // 9.5 g: the metal and the making follow the weight, the piece is untouched.
        $lighter = $this->pricing->breakdown($item, ['weight' => 9.5]);
        $this->assertEquals(9500, $lighter['metal_value']);
        $this->assertEquals(1710, $lighter['making']);
        $this->assertEquals(10, (float) $item->fresh()->weight);

        // 16% instead of 18%
        $bargained = $this->pricing->breakdown($item, ['making_rate' => 16]);
        $this->assertEquals(1600, $bargained['making']);
        $this->assertEquals(1800, $bargained['making_at_default'], 'what it would have been at the pricing rate, to show the saving');
        $this->assertStringContainsString('priced at 18%', collect($this->pricing->breakupLines($bargained))->firstWhere('label', 'Making charge')['note']);
    }

    public function test_the_making_rate_can_not_go_under_ten_percent_or_over_the_pricing_rate(): void
    {
        $item = $this->piece(10);

        $this->assertEquals(1000, $this->pricing->breakdown($item, ['making_rate' => 2])['making'], '10% of the metal value is the floor');
        $this->assertEquals(1800, $this->pricing->breakdown($item, ['making_rate' => 40])['making'], 'the rate the pricing gives is the top');
        $this->assertSame([10.0, 18.0], PricingService::makingBounds('percentage', 18));
        $this->assertSame([8.0, 8.0], PricingService::makingBounds('percentage', 8), 'a default already under the floor does not move');
        $this->assertSame([24.5, 245.0], PricingService::makingBounds('per_gram', 245));
        $this->assertSame([70.0, 700.0], PricingService::makingBounds('per_piece', 700));
    }

    public function test_an_amount_typed_in_is_kept_exactly_inside_the_range(): void
    {
        $item = $this->piece(10);

        $this->assertEquals(1500, $this->pricing->breakdown($item, ['making_amount' => 1500])['making']);
        $this->assertEquals(1000, $this->pricing->breakdown($item, ['making_amount' => 10])['making'], 'never under the floor');
        $this->assertEquals(1800, $this->pricing->breakdown($item, ['making_amount' => 5000])['making'], 'never over the pricing');
    }

    public function test_the_sale_keeps_the_weight_used_the_making_rate_and_leaves_the_piece_alone(): void
    {
        $item = $this->piece(10);

        $c = $this->sale($item)->set("cart.{$item->id}.weight_input", '9.5')->set("cart.{$item->id}.weight_note", 'scale')
            ->call('setMaking', $item->id, 'rate', 16);
        $line = $c->get('cart')[$item->id];
        $this->assertEquals(9.5, $line['weight']);
        $this->assertEquals(10, $line['weight_listed']);
        $this->assertEquals(9500 + 1520 + 100, $line['price']);   // 9.5 g, making 16% of 9,500, hallmarking
        $this->assertEquals(1710 - 1520, $line['making_saved']);

        $c->call('next')->call('next')->call('next')->call('submit')->assertHasNoErrors();

        $sale = Sale::latest('id')->first();
        $pivot = $sale->items()->first()->pivot;
        $this->assertEquals(9500 + 1520 + 100, (float) $pivot->price_at_sale);
        $this->assertEquals(10.0, (float) $pivot->weight_listed);
        $this->assertEquals(9.5, (float) $pivot->weight_at_sale);
        $this->assertSame('scale', $pivot->weight_note);
        $this->assertSame('percentage', $pivot->making_kind);
        $this->assertEquals(18, (float) $pivot->making_default_rate);
        $this->assertEquals(16, (float) $pivot->making_rate);
        $this->assertEquals(1520, (float) $pivot->making_amount);
        $this->assertEquals(10, (float) $item->fresh()->weight, 'the piece keeps its own weight');

        $this->get(route('sales.invoice', $sale))->assertOk()->assertSee('record 10.000 g')->assertSee('priced at 18%');
        $this->get(route('sales.bill', $sale))->assertOk()->assertSee('9.500 g');
    }

    public function test_a_tampered_cart_can_not_beat_the_floor_or_the_price(): void
    {
        $item = $this->piece(10);

        $c = $this->sale($item)->set("cart.{$item->id}.price", 1)->set("cart.{$item->id}.making_set", ['rate' => 0.5]);
        $c->call('next')->call('next')->call('next')->call('submit')->assertHasNoErrors();

        $pivot = Sale::latest('id')->first()->items()->first()->pivot;
        $this->assertEquals(10000 + 1000 + 100, (float) $pivot->price_at_sale, 'priced again on the server, at the floor');
    }

    public function test_the_discount_comes_after_the_round_off_and_can_not_touch_the_metal(): void
    {
        $item = $this->piece(10);
        // 10,000 + 1,800 + 100 = 11,900; GST 3% of 11,800 = 354, 18% of 100 = 18 -> 12,272 exactly, so no round-off here
        $c = $this->sale($item)->call('next');
        $c->set('finalType', 'flat')->set('finalValue', '72')->call('next');
        $this->assertEquals(12200, $c->get('total'));

        // Too much: more than the making charge (1,800) and its GST
        $c = $this->sale($this->piece(10))->call('next')->set('finalType', 'flat')->set('finalValue', '5000')->call('next');
        $c->assertHasErrors('finalValue');
        $this->assertSame(3, $c->get('step'), 'stays on the charges step');
    }

    public function test_a_final_price_gives_the_discount(): void
    {
        $item = $this->piece(10);

        $c = $this->sale($item)->call('next')->set('finalType', 'final')->set('finalValue', '12200')->call('next');
        $this->assertEquals(12200, $c->get('total'));
        $c->call('next')->call('submit')->assertHasNoErrors();

        $sale = Sale::latest('id')->first();
        $this->assertEquals(72, (float) $sale->final_discount);
        $this->assertEquals(12200, (float) $sale->total);
    }

    public function test_add_sale_on_a_piece_opens_a_bill_with_it_on_and_asks_for_the_customer(): void
    {
        $item = $this->piece();

        $this->get(route('stock.items.show', $item))->assertOk()->assertSee('Add sale')->assertSee(route('sales.new', ['item' => $item->id]), false);

        $c = Livewire::withQueryParams(['item' => $item->id])->test(NewSale::class);
        $this->assertSame(1, $c->get('step'), 'the customer comes first');
        $this->assertArrayHasKey($item->id, $c->get('cart'));
        $c->assertSee('On this bill');
        $c->set('customerId', Customer::create(['name' => 'From Piece', 'phone' => (string) random_int(6000000000, 9999999999)])->id)->call('next')
            ->assertSet('step', 2);
    }

    public function test_add_sale_is_not_offered_for_a_piece_that_can_not_be_sold(): void
    {
        $item = $this->piece();
        $item->update(['status' => 'sold']);

        $this->get(route('stock.items.show', $item))->assertOk()->assertDontSee('Add sale');
        $c = Livewire::withQueryParams(['item' => $item->id])->test(NewSale::class);
        $this->assertSame([], $c->get('cart'));
    }
}

<?php

namespace Tests\Feature\Movement;

use App\Livewire\Sales\NewSale;
use App\Livewire\Stock\BulkImport;
use App\Livewire\Stock\ItemForm;
use App\Models\Customer\Customer;
use App\Models\Movement\Movement;
use App\Models\Movement\RateLog;
use App\Models\Pricing\PricingRule;
use App\Models\Sales\Sale;
use App\Models\Stock\Item;
use App\Models\Stock\ItemCategory;
use App\Models\User;
use App\Support\ChargeNames;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * 25 October: a piece can carry additional charges by default (a name and an amount each, as many as it needs).
 * They are entered on the item form or in the import, start on every bill for the piece, and can be changed
 * or removed on the sale. Added after the GST, so not taxed.
 *   DB_CONNECTION=mysql DB_DATABASE=radharani_erp php artisan test --filter=DefaultChargesTest
 */
class DefaultChargesTest extends TestCase
{
    use DatabaseTransactions;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') === 'sqlite') {
            $this->markTestSkipped('Needs the MySQL database; see the note at the top of this class.');
        }
        $this->user = User::role('owner')->first() ?? $this->markTestSkipped('Needs the seeded owner user.');
        $this->actingAs($this->user);
        RateLog::create(['metal' => 'gold', 'purity' => '9K', 'rate' => 1000, 'source' => 'manual', 'updated_by' => $this->user->id]);
        PricingRule::whereIn('kind', ['making', 'additional', 'discount', 'hallmark'])->update(['active' => false]);
        app(\App\Services\PricingService::class)->forgetRules();
        ChargeNames::forget();
    }

    private function piece(?array $charges = null): Item
    {
        $item = Item::create(['packet_id' => $this->packetId(), 'internal_code' => 'DC' . random_int(100, 999), 'metal' => 'gold', 'category' => 'Sakha', 'purity' => '9K', 'making_value' => 0, 'weight' => 5, 'status' => 'in_stock',
            'default_additional_charges' => $charges]);
        Movement::create(['trackable_type' => 'item', 'trackable_id' => $item->id, 'movement_type' => 'vault_out', 'user_id' => $this->user->id]);

        return $item;
    }

    public function test_a_piece_keeps_its_default_charges_and_ignores_empty_ones(): void
    {
        $item = $this->piece([['name' => 'Sakha', 'amount' => 250], ['name' => '', 'amount' => ''], ['name' => 'Polish', 'amount' => 0]]);

        $this->assertSame([['name' => 'Sakha', 'amount' => 250.0]], $item->fresh()->defaultCharges());
    }

    public function test_the_item_form_saves_loads_and_checks_the_charges(): void
    {
        $category = ItemCategory::where('metal', 'gold')->where('is_active', true)->first() ?? $this->markTestSkipped('Needs a gold subcategory.');
        $form = Livewire::test(ItemForm::class)->call('open')
            ->set('metal', 'gold')->set('categoryId', $category->id)->set('purity', '22K')->set('weight', '5')->set('making_value', '0')
            ->set('charges', [['name' => 'Sakha', 'amount' => '250'], ['name' => 'Polish', 'amount' => '100']]);
        $form->call('save')->assertHasNoErrors();

        $item = Item::latest('id')->first();
        $this->assertSame([['name' => 'Sakha', 'amount' => 250.0], ['name' => 'Polish', 'amount' => 100.0]], $item->defaultCharges());

        // Opened again, the form shows them; a name without an amount is refused.
        $again = Livewire::test(ItemForm::class)->call('open', $item->id);
        $this->assertSame('Sakha', $again->get('charges')[0]['name']);
        $again->set('charges', [['name' => 'Lonely', 'amount' => '']])->call('save')->assertHasErrors('charges.0.amount');
    }

    public function test_the_import_reads_up_to_two_charges_per_row_and_flags_half_a_charge(): void
    {
        $this->assertArrayHasKey('charge1_name', BulkImport::FIELDS);
        $this->assertArrayHasKey('charge2_amount', BulkImport::FIELDS);

        $fields = array_keys(BulkImport::FIELDS);
        $c = Livewire::test(BulkImport::class)
            ->set('headers', ['Weight', 'Category', 'Additional charge 1', 'Additional amount 1', 'Additional charge 2', 'Additional amount 2'])
            ->set('rows', [['3', 'Ring', 'Sakha', '250', 'Polish', '100'], ['4', 'Ring', 'Sakha', '', '', ''], ['5', 'Ring', '', '', '', '']])
            ->set('mapping', ['weight', 'category', 'charge1_name', 'charge1_amount', 'charge2_name', 'charge2_amount'])
            ->set('defaultMetal', 'gold')->set('defaultMakingType', 'flat_per_piece')->set('defaultMakingValue', 0)
            ->call('confirmMapping');

        $rows = $c->get('reviewRows');
        $this->assertSame([['name' => 'Sakha', 'amount' => 250.0], ['name' => 'Polish', 'amount' => 100.0]], $rows[0]['data']['charges']);
        $this->assertNotEmpty($rows[1]['errors'], 'a name without an amount is a problem');
        $this->assertSame([], $rows[2]['data']['charges']);

        $c->call('confirmImport');
        $this->assertSame(250.0, Item::latest('id')->where('weight', 3)->first()->defaultCharges()[0]['amount']);
    }

    public function test_a_piece_brings_its_charges_to_the_bill_and_they_go_with_it(): void
    {
        $item = $this->piece([['name' => 'Sakha', 'amount' => 250]]);
        $other = $this->piece([['name' => 'Polish', 'amount' => 100]]);
        $customer = Customer::create(['name' => 'Charge Customer', 'phone' => (string) random_int(6000000000, 9999999999)]);

        $c = Livewire::test(NewSale::class)->set('customerId', $customer->id)->call('addItem', $item->id)->call('addItem', $other->id);
        $this->assertSame(['Sakha', 'Polish'], collect($c->get('extras'))->pluck('name')->all());

        $c->call('removeItem', $other->id);
        $this->assertSame(['Sakha'], collect($c->get('extras'))->pluck('name')->all(), 'the charge goes with its piece');

        // Staff can change the amount; it is added after the GST, so it is not taxed.
        $c->call('next')->set('extras.0.amount', '300')->call('next')->call('next')->call('next')->call('submit')->assertHasNoErrors();
        $sale = Sale::latest('id')->first();
        $this->assertEquals([['name' => 'Sakha', 'amount' => 300]], $sale->additional_charges);
        $this->assertEquals(5000 + 150 + 300, (float) $sale->total, '5 g of gold, 3% GST on it, and 300 added after the GST without tax');
    }

    public function test_the_names_offered_include_the_shops_own_and_those_already_on_pieces(): void
    {
        $this->piece([['name' => 'Tikli work', 'amount' => 60]]);
        ChargeNames::forget();

        $names = ChargeNames::list();
        $this->assertContains('Sakha', $names);
        $this->assertContains('Tikli work', $names);
        $this->assertSame($names, collect($names)->unique(fn ($n) => mb_strtolower($n))->values()->all(), 'no duplicates');
    }
}

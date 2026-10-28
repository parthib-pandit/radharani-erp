<?php

namespace Tests\Feature\Movement;

use App\Livewire\Admin\CustomerManager;
use App\Livewire\Sales\NewSale;
use App\Livewire\Sales\SaleVerificationQueue;
use App\Models\Customer\Customer;
use App\Models\Location;
use App\Models\Movement\Movement;
use App\Models\Movement\RateLog;
use App\Models\Notification\PendingNotification;
use App\Models\Pricing\PricingRule;
use App\Models\Pricing\TaxSetting;
use App\Models\Sales\Sale;
use App\Models\Stock\Item;
use App\Models\User;
use App\Services\LedgerService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * 28 October: the new sale flow. Items, This bill (one full screen with the charges, round off and discount in it),
 * Customer (the shared Walk-in customer is one tap), Payment (cash or bank), Review (with a copyable detail). A piece
 * still in the vault gets a Move to Counter popup, and nothing moves until it is pressed.
 *   DB_CONNECTION=mysql DB_DATABASE=radharani_erp php artisan test --filter=SaleFlowTest
 */
class SaleFlowTest extends TestCase
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

        TaxSetting::current()->update(['jewellery_percent' => 3, 'hallmark_percent' => 18]);
        RateLog::create(['metal' => 'gold', 'purity' => '9K', 'rate' => 1000, 'source' => 'manual', 'updated_by' => $this->user->id]);
        PricingRule::whereIn('kind', ['making', 'additional', 'discount', 'hallmark'])->update(['active' => false]);
        app(\App\Services\PricingService::class)->forgetRules();
    }

    private function piece(bool $atCounter = true): Item
    {
        $item = Item::create(['packet_id' => $this->packetId(), 'internal_code' => 'FL' . random_int(100, 999), 'metal' => 'gold', 'category' => 'Ring', 'purity' => '9K', 'making_value' => 0, 'weight' => 5, 'status' => 'in_stock']);
        if ($atCounter) {
            Movement::create(['trackable_type' => 'item', 'trackable_id' => $item->id, 'movement_type' => 'vault_out', 'user_id' => $this->user->id]);
        }

        return $item;
    }

    public function test_the_steps_are_items_this_bill_customer_payment_review(): void
    {
        $this->assertSame([1 => 'Items', 2 => 'This bill', 3 => 'Customer', 4 => 'Payment', 5 => 'Review'], NewSale::STEPS);

        $c = Livewire::test(NewSale::class)->assertSet('step', 1)->assertSee('What is being sold');
        $c->call('addItem', $this->piece()->id)->call('next')->assertSet('step', 2)
            ->assertSee('This bill')->assertSee('Round off')->assertSee('Additional charges')->assertSee('Final price to pay')
            ->assertDontSee('Round off and discount');
    }

    public function test_there_is_one_walk_in_customer_and_it_is_not_offered_where_someone_has_to_be_reached(): void
    {
        $walkIn = Customer::walkIn();
        $this->assertTrue($walkIn->is_walk_in);
        $this->assertSame(1, Customer::where('is_walk_in', true)->count());
        $this->assertSame($walkIn->id, Customer::walkIn()->id, 'always the same record');
        $this->assertNull($walkIn->password, 'nobody can sign in as it');

        $this->assertFalse(Customer::real()->whereKey($walkIn->id)->exists());
        // typing its name finds nothing on the sale's own customer search
        $c = Livewire::test(NewSale::class)->call('addItem', $this->piece()->id)->call('next')->call('next')->set('customerSearch', 'Walk-in');
        $this->assertTrue($c->viewData('customerResults')->isEmpty());
        $c->assertSee('Walk-in customer');   // but the one-tap tile is there
    }

    public function test_a_sale_to_the_walk_in_customer_is_saved_without_a_referral_and_gets_no_confirmation_message(): void
    {
        $item = $this->piece();
        $c = Livewire::test(NewSale::class)->call('addItem', $item->id)->call('next')->call('next')
            ->set('referralCode', 'ABC123')->call('chooseWalkIn')
            ->assertSet('customerId', Customer::walkIn()->id)->assertSet('referralCode', '');
        $c->call('next')->set('payments', [['mode' => 'cash', 'amount' => '1000']])->call('next')->call('submit')->assertHasNoErrors();

        $sale = Sale::latest('id')->first();
        $this->assertSame(Customer::walkIn()->id, $sale->customer_id);
        $this->assertNull($sale->referral_customer_id);

        $before = PendingNotification::where('type', 'sale_confirmation')->count();
        Livewire::test(SaleVerificationQueue::class)->call('startVerify', $sale->id)->set('tallyNumber', 'W' . random_int(100000, 999999))->call('verify');
        $this->assertTrue((bool) $sale->fresh()->confirmed_by_accountant);
        $this->assertSame($before, PendingNotification::where('type', 'sale_confirmation')->count(), 'no number to send it to');

        // it has its own ledger like any customer
        $ledger = app(LedgerService::class)->forCustomer(Customer::walkIn());
        $this->assertIsArray($ledger);
    }

    public function test_a_real_customer_is_still_asked_for_and_the_step_needs_someone(): void
    {
        $c = Livewire::test(NewSale::class)->call('addItem', $this->piece()->id)->call('next')->call('next')
            ->call('next')->assertHasErrors('customerId')->assertSet('step', 3);

        $customer = Customer::create(['name' => 'Real Buyer', 'phone' => (string) random_int(6000000000, 9999999999)]);
        $c->call('chooseCustomer', $customer->id)->call('next')->assertSet('step', 4);
    }

    public function test_a_piece_in_the_vault_opens_the_move_to_counter_popup_and_nothing_moves_until_it_is_pressed(): void
    {
        $item = $this->piece(false);   // never taken out: recorded as in the vault
        $this->assertTrue(Location::isInVault($item));
        $moves = Movement::count();

        $c = Livewire::test(NewSale::class)->call('addItem', $item->id)
            ->assertSet('showVaultMove', true)->assertSee('Move to Counter')->assertSee($item->label);
        $this->assertSame($moves, Movement::count(), 'adding it moved nothing');

        // Not now: it stays on the bill, and going on opens the popup again instead of an error
        $c->set('showVaultMove', false)->call('next')->assertSet('step', 1)->assertSet('showVaultMove', true);
        $this->assertSame($moves, Movement::count());

        $c->call('moveVaultPiecesToCounter')->assertSet('showVaultMove', false);
        $this->assertSame($moves + 1, Movement::count());
        $this->assertFalse(Location::isInVault($item->fresh()));
        $this->assertSame('vault_out', Movement::latest('id')->first()->movement_type);

        $c->call('next')->assertSet('step', 2);
    }

    public function test_staff_who_can_not_move_stock_can_not_press_move_to_counter(): void
    {
        $item = $this->piece(false);
        $c = Livewire::test(NewSale::class)->call('addItem', $item->id);
        $staff = User::whereDoesntHave('permissions')->get()->first(fn ($u) => ! $u->can('movement.create') && $u->is_active);
        if (! $staff) {
            $this->markTestSkipped('Needs an active user without movement.create.');
        }
        $this->actingAs($staff);
        $moves = Movement::count();
        try {
            $c->call('moveVaultPiecesToCounter');
        } catch (\Throwable $e) {
        }
        $this->assertSame($moves, Movement::count());
    }

    public function test_payment_is_cash_or_bank_shown_as_icons(): void
    {
        $customer = Customer::create(['name' => 'Pay Buyer', 'phone' => (string) random_int(6000000000, 9999999999)]);
        $c = Livewire::test(NewSale::class)->call('addItem', $this->piece()->id)->call('next')->call('next')->call('chooseCustomer', $customer->id)->call('next')
            ->assertSee('Cash')->assertSee('Bank')->assertDontSee('UPI')->assertDontSee('Cheque')->assertDontSee('Card');
        $this->assertSame(['cash' => 'Cash', 'bank' => 'Bank'], $c->viewData('modes'));

        $c->set('payments.0.mode', 'bank')->assertSet('payments.0.mode', 'bank')->call('next')->call('submit')->assertHasNoErrors();
        $this->assertSame('bank', Sale::latest('id')->first()->payments()->first()->mode);
    }

    public function test_review_has_the_whole_bill_as_copyable_text(): void
    {
        $item = $this->piece();
        $customer = Customer::create(['name' => 'Copy Buyer', 'phone' => '9876543210']);
        $c = Livewire::test(NewSale::class)->call('addItem', $item->id)->call('next')
            ->set('extras', [['name' => 'Sakha', 'amount' => '250']])->set('notes', 'Collect on Friday')
            ->call('next')->call('chooseCustomer', $customer->id)->call('next')->call('next')->assertSet('step', 5);

        $text = $c->viewData('copyText');
        foreach (['Copy Buyer', '9876543210', $item->label, 'Ring', '5.000 g', 'Gold value', 'Sakha: ₹250.00', 'Total: ₹', 'Paid by Cash', 'Balance: ₹0.00', 'Note: Collect on Friday'] as $part) {
            $this->assertStringContainsString($part, $text);
        }
        $c->assertSee('Copy details')->assertSee('Save sale')->assertDontSee('@js(', false);   // the text is in the page, not a raw directive
    }

    public function test_the_admin_customer_list_still_shows_the_walk_in_so_its_ledger_can_be_opened(): void
    {
        $this->assertTrue(Customer::where('is_walk_in', true)->exists());
        Livewire::test(CustomerManager::class)->set('search', 'Walk-in')->assertSee('Walk-in customer');
    }

    public function test_cash_and_bank_are_the_only_modes_for_a_later_payment_and_for_a_customer_advance(): void
    {
        $this->assertSame(['cash', 'bank'], array_keys(\App\Models\Customer\CustomerAdvance::NEW_MODES));
        $this->assertSame(['cash', 'bank'], array_keys(\App\Models\Orders\OrderPayment::NEW_MODES));

        $customer = Customer::create(['name' => 'Advance Buyer', 'phone' => (string) random_int(6000000000, 9999999999)]);
        Livewire::test(\App\Livewire\Admin\CustomerDetail::class, ['customer' => $customer])->set('advanceAmount', '500')->set('advanceMode', 'cheque')->call('receiveAdvance')->assertHasErrors('advanceMode');
    }

    public function test_every_amount_field_is_a_plain_field_so_a_scroll_never_changes_it(): void
    {
        $offenders = [];
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(resource_path('views'))) as $f) {
            if ($f->isFile() && str_ends_with($f->getFilename(), '.blade.php') && str_contains(file_get_contents($f->getPathname()), 'type="number"')) {
                $offenders[] = str_replace(resource_path('views') . '/', '', $f->getPathname());
            }
        }
        $this->assertSame([], $offenders, 'a number field changes its value when the mouse wheel is scrolled over it');
    }
}

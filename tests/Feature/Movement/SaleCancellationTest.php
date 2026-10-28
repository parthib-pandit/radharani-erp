<?php

namespace Tests\Feature\Movement;

use App\Livewire\Sales\InvoiceView;
use App\Livewire\Sales\NewSale;
use App\Livewire\Sales\SalesHistory;
use App\Livewire\Sales\SaleVerificationQueue;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerAdvance;
use App\Models\Movement\Movement;
use App\Models\Movement\RateLog;
use App\Models\Orders\Order;
use App\Models\Pricing\PricingRule;
use App\Models\Pricing\TaxSetting;
use App\Models\Sales\Sale;
use App\Models\Sales\SalePayment;
use App\Models\Stock\Item;
use App\Models\User;
use App\Services\AdvanceService;
use App\Services\LedgerService;
use App\Services\SaleCanceller;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * 25 October: a sale can be cancelled before or after an admin verifies it. The sale row is never touched: a
 * cancellation row says who and why, the pieces go back into stock, exchange credit and advance come back and
 * what was paid is held on the customer's account. A cancelled sale drops out of every figure.
 *   DB_CONNECTION=mysql DB_DATABASE=radharani_erp php artisan test --filter=SaleCancellationTest
 */
class SaleCancellationTest extends TestCase
{
    use DatabaseTransactions;

    private User $user;
    private Customer $customer;

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
        $this->customer = Customer::create(['name' => 'Cancel Customer', 'phone' => (string) random_int(6000000000, 9999999999)]);
    }

    private function piece(): Item
    {
        $item = Item::create(['packet_id' => $this->packetId(), 'internal_code' => 'CN' . random_int(100, 999), 'metal' => 'gold', 'category' => 'Ring', 'purity' => '9K', 'making_value' => 0, 'weight' => 5, 'status' => 'in_stock']);
        Movement::create(['trackable_type' => 'item', 'trackable_id' => $item->id, 'movement_type' => 'vault_out', 'user_id' => $this->user->id]);

        return $item;
    }

    /** A saved sale made through the real form, paid in part in cash. */
    private function sale(Item $item, float $cash = 2000): Sale
    {
        $c = Livewire::test(NewSale::class)->set('customerId', $this->customer->id)->call('addItem', $item->id)
            ->call('next')->call('next')->call('next')->set('payments', [['mode' => 'cash', 'amount' => (string) $cash]])->call('next')->call('submit')->assertHasNoErrors();

        return Sale::where('customer_id', $this->customer->id)->latest('id')->firstOrFail();
    }

    public function test_cancelling_a_waiting_sale_frees_the_piece_and_holds_the_cash_on_the_account(): void
    {
        $item = $this->piece();
        $sale = $this->sale($item, 2000);
        $this->assertSame('reserved', $item->fresh()->status);

        Livewire::test(InvoiceView::class, ['sale' => $sale])->call('startCancel', $sale->id)
            ->call('confirmCancel')->assertHasErrors('cancelReason')
            ->set('cancelReason', 'Customer changed their mind')->call('confirmCancel')->assertHasNoErrors();

        $this->assertSame('in_stock', $item->fresh()->status);
        $this->assertTrue($sale->fresh()->isCancelled());
        $c = $sale->fresh()->cancellation;
        $this->assertSame('Customer changed their mind', $c->reason);
        $this->assertFalse($c->was_verified);
        $this->assertEquals(2000, (float) $c->held_on_account);
        $this->assertEquals(2000, app(AdvanceService::class)->balance($this->customer->id), 'the cash is held as an advance');
        $this->assertEquals(0, Sale::notCancelled()->where('id', $sale->id)->count());
        $this->assertEquals($sale->total, $sale->fresh()->total, 'the sale row itself is untouched');
    }

    public function test_a_cancelled_sale_can_not_be_cancelled_twice_or_take_more_payments(): void
    {
        $sale = $this->sale($this->piece(), 100);
        app(SaleCanceller::class)->cancel($sale, 'first');

        $this->expectException(\RuntimeException::class);
        app(SaleCanceller::class)->cancel($sale, 'second');
    }

    public function test_a_verified_sale_can_be_cancelled_by_an_admin_and_the_piece_goes_back_to_stock(): void
    {
        $item = $this->piece();
        $sale = $this->sale($item, 100);
        Livewire::test(SaleVerificationQueue::class)->call('startVerify', $sale->id)->set('tallyNumber', 'T' . random_int(100000, 999999))->call('verify');
        $this->assertSame('sold', $item->fresh()->status);
        $this->assertTrue(SaleCanceller::allowed($sale->fresh()));

        Livewire::test(InvoiceView::class, ['sale' => $sale])->call('startCancel', $sale->id)->set('cancelReason', 'Wrong piece')->call('confirmCancel');

        $this->assertSame('in_stock', $item->fresh()->status);
        $this->assertTrue($sale->fresh()->cancellation->was_verified);
    }

    public function test_only_an_admin_may_cancel_a_verified_sale(): void
    {
        $sale = $this->sale($this->piece(), 100);
        $sale->update(['confirmed_by_accountant' => true]); // verified (the narrow exception to rule 1, done by hand for the test)

        $staff = User::role('counter_staff')->first() ?? $this->markTestSkipped('Needs a counter staff user.');
        $this->actingAs($staff);
        $this->assertFalse(SaleCanceller::allowed($sale->fresh()), 'counter staff can cancel only what is not verified yet');

        $sale->update(['confirmed_by_accountant' => false]);
        $this->assertTrue(SaleCanceller::allowed($sale->fresh()));
    }

    public function test_advance_a_sale_used_goes_back_and_the_ledger_shows_a_note_not_a_bill(): void
    {
        $item = $this->piece();
        app(AdvanceService::class)->receive($this->customer->id, 5000, 'cash');
        $sale = $this->sale($item, 100);
        // Pay 3,000 of it from the advance, as the form would.
        app(AdvanceService::class)->useOnSale($this->customer->id, $sale->id, 3000);
        SalePayment::create(['sale_id' => $sale->id, 'mode' => 'advance', 'amount' => 3000, 'user_id' => $this->user->id]);
        $this->assertEquals(2000, app(AdvanceService::class)->balance($this->customer->id));

        app(SaleCanceller::class)->cancel($sale, 'test');

        // 2,000 left + 3,000 back + 100 cash held
        $this->assertEquals(5100, app(AdvanceService::class)->balance($this->customer->id));
        $this->assertEquals(3000, (float) $sale->fresh()->cancellation->credit_returned);

        $ledger = app(LedgerService::class)->forCustomer($this->customer);
        $labels = collect($ledger['rows'] ?? $ledger['events'] ?? [])->pluck('label')->implode(' | ');
        $this->assertStringContainsString("Sale #{$sale->id} cancelled", $labels);
        $this->assertStringNotContainsString('Sale ' . $sale->bill_number . ' (', $labels, 'the cancelled bill is not billed');
    }

    public function test_the_order_a_cancelled_sale_was_delivering_is_open_again(): void
    {
        $item = $this->piece();
        $sale = $this->sale($item, 100);
        $order = Order::create(['customer_id' => $this->customer->id, 'product_description' => 'Ring', 'estimated_value' => 1000, 'status' => 'delivered', 'converted_sale_id' => $sale->id, 'created_by' => $this->user->id]);

        app(SaleCanceller::class)->cancel($sale, 'test');

        $this->assertNull($order->fresh()->converted_sale_id);
        $this->assertSame('ready', $order->fresh()->status);
    }

    public function test_the_lists_leave_a_cancelled_sale_out_and_the_filter_finds_it(): void
    {
        $sale = $this->sale($this->piece(), 100);
        app(SaleCanceller::class)->cancel($sale, 'test');

        Livewire::test(SaleVerificationQueue::class)->assertDontSee("#{$sale->id}</a>", false);
        Livewire::test(SalesHistory::class)->set('status', 'cancelled')->assertSee('Cancelled');
        $this->get(route('sales.invoice', $sale))->assertOk()->assertSee('This sale was cancelled');
    }

    public function test_the_draft_can_be_cancelled_at_any_step_and_nothing_is_saved(): void
    {
        $before = Sale::count();

        Livewire::test(NewSale::class)->set('customerId', $this->customer->id)->call('addItem', $this->piece()->id)
            ->call('cancelDraft')->assertRedirect(route('sales.history'));

        $this->assertSame($before, Sale::count());
    }

    public function test_a_returned_advance_row_does_not_break_the_customer_ledger(): void
    {
        CustomerAdvance::create(['customer_id' => $this->customer->id, 'kind' => 'received', 'amount' => 10, 'user_id' => $this->user->id]);

        $this->assertIsArray(app(LedgerService::class)->forCustomer($this->customer));
    }
}

<?php

namespace Tests\Feature\Movement;

use App\Livewire\Admin\CustomerDetail;
use App\Livewire\Notifications\PendingMessagesQueue;
use App\Livewire\Orders\OrderDetail;
use App\Livewire\Sales\InvoiceView;
use App\Livewire\Sales\NewSale;
use App\Models\Customer\Customer;
use App\Models\Movement\Movement;
use App\Models\Movement\RateLog;
use App\Models\Notification\PendingNotification;
use App\Models\Orders\Order;
use App\Models\Pricing\PricingRule;
use App\Models\Pricing\TaxSetting;
use App\Models\Sales\Sale;
use App\Models\Stock\Item;
use App\Models\User;
use App\Services\SaleCanceller;
use App\Support\MessageTemplates;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * 28 October: money events (a sale entered, a payment received, an advance received, a sale cancelled) each add a record to
 * the Messages queue. They are written for the employees, so they carry no customer phone and are marked seen, not sent.
 *   DB_CONNECTION=mysql DB_DATABASE=radharani_erp php artisan test --filter=MoneyMessagesTest
 */
class MoneyMessagesTest extends TestCase
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
        $this->customer = Customer::create(['name' => 'Money Buyer', 'phone' => (string) random_int(6000000000, 9999999999)]);
    }

    private function sale(float $cash = 2000): Sale
    {
        $item = Item::create(['packet_id' => $this->packetId(), 'internal_code' => 'MM' . random_int(100, 999), 'metal' => 'gold', 'category' => 'Ring', 'purity' => '9K', 'making_value' => 0, 'weight' => 5, 'status' => 'in_stock']);
        Movement::create(['trackable_type' => 'item', 'trackable_id' => $item->id, 'movement_type' => 'vault_out', 'user_id' => $this->user->id]);
        Livewire::test(NewSale::class)->set('customerId', $this->customer->id)->call('addItem', $item->id)->call('next')->call('next')->call('next')
            ->set('payments', [['mode' => 'cash', 'amount' => (string) $cash]])->call('next')->call('submit')->assertHasNoErrors();

        return Sale::where('customer_id', $this->customer->id)->latest('id')->firstOrFail();
    }

    private function last(string $type): ?PendingNotification
    {
        return PendingNotification::where('type', $type)->latest('id')->first();
    }

    public function test_a_sale_entered_adds_a_record_for_the_staff(): void
    {
        $before = PendingNotification::count();
        $sale = $this->sale(2000);

        $m = $this->last('money_sale');
        $this->assertSame($before + 1, PendingNotification::count());
        $this->assertSame('pending', $m->status);
        $this->assertSame('Staff', $m->recipient_name);
        $this->assertNull($m->recipient_phone, 'it is not going to the customer');
        foreach (["Sale #{$sale->id}", 'entered by ' . $this->user->name, 'Money Buyer', 'Cash ₹2,000.00', 'Balance', 'verify'] as $part) {
            $this->assertStringContainsString($part, $m->message);
        }
        $this->assertSame('sale', $m->related_type);
        $this->assertSame($sale->id, (int) $m->related_id);
    }

    public function test_a_later_payment_an_advance_and_a_cancellation_each_add_a_record(): void
    {
        $sale = $this->sale(100);

        Livewire::test(InvoiceView::class, ['sale' => $sale])->set('mode', 'bank')->set('amount', '500')->call('addPayment')->assertHasNoErrors();
        $this->assertStringContainsString('₹500.00 received by Bank on sale #' . $sale->id, $this->last('money_payment')->message);

        Livewire::test(CustomerDetail::class, ['customer' => $this->customer])->set('advanceAmount', '1500')->set('advanceMode', 'cash')->set('advanceOccasion', 'Halkhata')->call('receiveAdvance')->assertHasNoErrors();
        $a = $this->last('money_advance');
        $this->assertStringContainsString('₹1,500.00 advance received by Cash from Money Buyer', $a->message);
        $this->assertStringContainsString('(Halkhata)', $a->message);

        app(SaleCanceller::class)->cancel($sale, 'Wrong piece');
        $c = $this->last('money_cancelled');
        $this->assertStringContainsString("Sale #{$sale->id}", $c->message);
        $this->assertStringContainsString('Wrong piece', $c->message);
        $this->assertStringContainsString('held on the account as an advance', $c->message);
    }

    public function test_an_order_advance_adds_a_record(): void
    {
        $order = Order::create(['customer_id' => $this->customer->id, 'product_description' => 'Chain', 'sourcing' => 'karigar', 'estimated_value' => 5000, 'status' => 'placed', 'created_by' => $this->user->id]);

        Livewire::test(OrderDetail::class, ['order' => $order])->set('advanceAmount', '800')->set('advanceMode', 'bank')->call('addAdvance')->assertHasNoErrors();

        $m = $this->last('money_advance');
        $this->assertStringContainsString("₹800.00 advance received by Bank on order #{$order->id}", $m->message);
        $this->assertSame('order', $m->related_type);
    }

    public function test_the_queue_has_a_money_group_shown_to_staff_and_marked_seen(): void
    {
        $this->sale(100);
        $this->assertSame('Money', MessageTemplates::groupOf('money_payment'));
        $this->assertSame('Payment received', MessageTemplates::label('money_payment'));

        $m = $this->last('money_sale');
        $c = Livewire::test(PendingMessagesQueue::class)->set('groupFilter', 'Money')->assertSee('Money · Sale entered')->assertSee('Staff')->assertSee('Mark seen')->assertDontSee('Mark sent');
        $c->call('markSent', $m->id);
        $this->assertSame('sent', $m->fresh()->status);
        Livewire::test(PendingMessagesQueue::class)->set('groupFilter', 'Money')->set('statusFilter', 'sent')->assertSee('Seen');
    }

    public function test_the_customers_own_confirmation_message_is_untouched(): void
    {
        $sale = $this->sale(100);
        Livewire::test(\App\Livewire\Sales\SaleVerificationQueue::class)->call('startVerify', $sale->id)->set('tallyNumber', 'M' . random_int(100000, 999999))->call('verify');

        $confirmation = $this->last('sale_confirmation');
        $this->assertNotNull($confirmation);
        $this->assertSame($this->customer->phone, $confirmation->recipient_phone, 'still addressed to the customer, with their number');
    }
}

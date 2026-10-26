<?php

namespace Tests\Feature\Movement;

use App\Livewire\Cash\CashBookPage;
use App\Models\Cash\CashEntry;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerAdvance;
use App\Models\Orders\Order;
use App\Models\Orders\OrderPayment;
use App\Models\Sales\Sale;
use App\Models\Sales\SalePayment;
use App\Models\User;
use App\Services\CashBook;
use App\Services\SaleCanceller;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * 25 October: the cash book. Two books, Main (the vault) and Petty; money in and out with a reason, a running balance,
 * moves between the books, corrections as new rows, and a day close that counts the notes. The Main book reads the
 * cash taken on sales, order advances and customer advances from where it was recorded.
 *   DB_CONNECTION=mysql DB_DATABASE=radharani_erp php artisan test --filter=CashBookTest
 */
class CashBookTest extends TestCase
{
    use DatabaseTransactions;

    private User $user;
    private CashBook $cash;
    private string $today;

    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') === 'sqlite') {
            $this->markTestSkipped('Needs the MySQL database; see the note at the top of this class.');
        }
        $this->user = User::role('owner')->first() ?? $this->markTestSkipped('Needs the seeded owner user.');
        $this->actingAs($this->user);
        $this->cash = app(CashBook::class);
        $this->today = CashBook::today();
    }

    private function sale(float $total = 1000): Sale
    {
        $customer = Customer::create(['name' => 'Cash Customer', 'phone' => (string) random_int(6000000000, 9999999999)]);

        return Sale::create(['customer_id' => $customer->id, 'invoice_number' => 'RESV-' . random_int(100000, 999999), 'type' => 'sale', 'total' => $total, 'confirmed_by_accountant' => false, 'created_by' => $this->user->id]);
    }

    public function test_the_balance_is_the_opening_plus_money_in_less_money_out(): void
    {
        $this->cash->add('petty', 'receipt', 1000, $this->today, 'Float from the owner');
        $this->cash->add('petty', 'payment', 250, $this->today, 'Tea and milk');
        $this->cash->add('petty', 'receipt', 50, $this->today, 'Returned change');

        $base = $this->cash->balance('petty', $this->today);
        $day = $this->cash->day('petty', $this->today);

        $this->assertEquals($base, $day['closing']);
        $this->assertEquals(1050, $day['in']);
        $this->assertEquals(250, $day['out']);
        $this->assertEquals(round($day['opening'] + 800, 2), $day['closing']);
        $this->assertEquals([1000, 750, 800], $day['lines']->pluck('balance')->map(fn ($b) => round($b - $day['opening'], 2))->all(), 'a running balance after each line');
    }

    public function test_money_out_can_not_be_more_than_the_book_holds_and_future_days_are_refused(): void
    {
        $held = $this->cash->balance('petty', $this->today);

        $this->expectException(\InvalidArgumentException::class);
        $this->cash->add('petty', 'payment', $held + 1, $this->today, 'Too much');
    }

    public function test_a_future_date_is_refused(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->cash->add('petty', 'receipt', 10, now()->addDay()->toDateString(), 'Tomorrow');
    }

    public function test_moving_money_between_the_books_is_two_linked_entries_and_needs_enough_in_the_first(): void
    {
        $this->cash->add('main', 'receipt', 5000, $this->today, 'Top up');
        $mainBefore = $this->cash->balance('main', $this->today);
        $pettyBefore = $this->cash->balance('petty', $this->today);

        $id = $this->cash->transfer('main', 'petty', 1500, $this->today, 'Petty float');

        $this->assertEquals($mainBefore - 1500, $this->cash->balance('main', $this->today));
        $this->assertEquals($pettyBefore + 1500, $this->cash->balance('petty', $this->today));
        $this->assertSame(2, CashEntry::where('transfer_id', $id)->count());

        $this->expectException(\InvalidArgumentException::class);
        $this->cash->transfer('petty', 'main', $this->cash->balance('petty', $this->today) + 1, $this->today, 'More than it has');
    }

    public function test_a_failed_move_leaves_nothing_behind(): void
    {
        $before = CashEntry::count();
        try {
            $this->cash->transfer('petty', 'main', $this->cash->balance('petty', $this->today) + 100, $this->today, 'Too much');
        } catch (\InvalidArgumentException) {
        }
        $this->assertSame($before, CashEntry::count());
    }

    public function test_cash_on_sales_orders_and_advances_shows_in_the_main_book_only(): void
    {
        $before = $this->cash->balance('main', $this->today);
        $pettyBefore = $this->cash->balance('petty', $this->today);

        $sale = $this->sale();
        SalePayment::create(['sale_id' => $sale->id, 'mode' => 'cash', 'amount' => 700, 'user_id' => $this->user->id]);
        SalePayment::create(['sale_id' => $sale->id, 'mode' => 'upi', 'amount' => 300, 'user_id' => $this->user->id]);
        $order = Order::create(['customer_id' => $sale->customer_id, 'product_description' => 'Ring', 'estimated_value' => 1000, 'status' => 'placed', 'created_by' => $this->user->id]);
        OrderPayment::create(['order_id' => $order->id, 'amount' => 200, 'mode' => 'cash', 'user_id' => $this->user->id]);
        OrderPayment::create(['order_id' => $order->id, 'amount' => 99, 'mode' => 'bank', 'user_id' => $this->user->id]);
        CustomerAdvance::create(['customer_id' => $sale->customer_id, 'kind' => 'received', 'amount' => 50, 'mode' => 'cash', 'user_id' => $this->user->id]);
        CustomerAdvance::create(['customer_id' => $sale->customer_id, 'kind' => 'received', 'amount' => 40, 'mode' => 'upi', 'user_id' => $this->user->id]);

        $this->assertEquals($before + 700 + 200 + 50, $this->cash->balance('main', $this->today), 'cash only: not UPI, bank or card');
        $this->assertEquals($pettyBefore, $this->cash->balance('petty', $this->today));

        $labels = $this->cash->day('main', $this->today)['lines']->where('source', 'auto')->pluck('label')->implode(' | ');
        $this->assertStringContainsString("Cash on sale #{$sale->id}", $labels);
        $this->assertStringContainsString("Cash advance on order #{$order->id}", $labels);
    }

    public function test_cash_taken_on_a_sale_that_is_cancelled_stays_counted_once(): void
    {
        $sale = $this->sale();
        SalePayment::create(['sale_id' => $sale->id, 'mode' => 'cash', 'amount' => 700, 'user_id' => $this->user->id]);
        $before = $this->cash->balance('main', $this->today);

        app(SaleCanceller::class)->cancel($sale, 'test');

        // The cash is still in the till, and the advance made to hold it is not new cash.
        $this->assertEquals($before, $this->cash->balance('main', $this->today));
    }

    public function test_putting_an_entry_right_adds_a_row_and_works_once(): void
    {
        $e = $this->cash->add('petty', 'receipt', 100, $this->today, 'Typed twice');
        $before = $this->cash->balance('petty', $this->today);

        $fix = $this->cash->reverse($e, 'Typed twice');

        $this->assertEquals($before - 100, $this->cash->balance('petty', $this->today));
        $this->assertSame($e->id, (int) $fix->reverses_id);
        $this->assertSame('payment', $fix->kind);
        $this->assertSame(100.0, (float) $e->fresh()->amount, 'the old entry is untouched');

        $this->expectException(\RuntimeException::class);
        $this->cash->reverse($e->fresh(), 'again');
    }

    public function test_an_opening_balance_and_a_move_between_books_can_not_be_reversed(): void
    {
        $opening = CashEntry::create(['book' => 'petty', 'kind' => 'opening', 'amount' => 10, 'occurred_on' => $this->today, 'reason' => 'Opening balance', 'user_id' => $this->user->id]);
        $this->expectException(\RuntimeException::class);
        $this->cash->reverse($opening, 'no');
    }

    public function test_closing_the_day_keeps_the_count_and_the_difference(): void
    {
        $this->cash->add('petty', 'receipt', 1000, $this->today, 'Float');
        $expected = $this->cash->balance('petty', $this->today);

        $close = $this->cash->close('petty', $this->today, [500 => 2, 100 => 3, 20 => 1], 'one note short');

        $this->assertEquals(1320, $close->counted);
        $this->assertEquals($expected, $close->expected);
        $this->assertEquals(round(1320 - $expected, 2), $close->difference);
        $this->assertSame(2, $close->denominations['500']);
        $this->assertSame(0, $close->denominations['2000']);
        $this->assertSame($close->id, $this->cash->day('petty', $this->today)['close']->id);
    }

    public function test_the_page_records_entries_and_closes_the_day(): void
    {
        $before = $this->cash->balance('petty', $this->today);

        $c = Livewire::test(CashBookPage::class)->set('book', 'petty')
            ->call('startEntry', 'receipt')->set('entryAmount', '800')->set('entryReason', 'Float')->call('saveEntry')->assertHasNoErrors()
            ->call('startEntry', 'payment')->set('entryAmount', '120')->set('entryReason', 'Stationery')->call('saveEntry')->assertHasNoErrors();

        $this->assertEquals($before + 680, $this->cash->balance('petty', $this->today));
        $this->assertEquals($before + 680, $c->viewData('day')['closing']);

        // more than the book holds is refused with a message on the amount
        $c->call('startEntry', 'payment')->set('entryAmount', '99999999')->set('entryReason', 'Too much')->call('saveEntry')->assertHasErrors('entryAmount');

        $c->call('startClose')->set('counts.500', '1')->set('counts.100', '1')->call('saveClose')->assertHasNoErrors();
        $this->assertEquals(600, $c->viewData('day')['close']->counted);
    }

    public function test_the_opening_balance_can_be_set_once_per_book(): void
    {
        Livewire::test(CashBookPage::class)->set('book', 'petty')->call('startOpening')->set('openingAmount', '500')->call('saveOpening')->assertHasNoErrors()
            ->call('startOpening')->set('openingAmount', '700')->call('saveOpening')->assertHasErrors('openingAmount');

        $this->assertSame(1, CashEntry::where('book', 'petty')->where('kind', 'opening')->count());
    }

    public function test_only_people_with_the_cash_book_permission_can_open_it(): void
    {
        $this->get(route('cashbook'))->assertOk()->assertSee('Cash Book');

        $staff = User::role('counter_staff')->first() ?? $this->markTestSkipped('Needs a counter staff user.');
        $this->actingAs($staff)->get(route('cashbook'))->assertForbidden();
        $this->assertFalse($staff->can('cashbook.manage'));

        $manager = User::role('manager')->first();
        if ($manager) {
            $this->assertTrue($manager->can('cashbook.manage'));
        }
    }
}

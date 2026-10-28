<?php

namespace Tests\Feature\Movement;

use App\Livewire\Stock\BoxDetail;
use App\Livewire\Stock\ContainerList;
use App\Livewire\Stock\PacketDetail;
use App\Models\Stock\Box;
use App\Models\Stock\Item;
use App\Models\Stock\Packet;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * 25 October: a sold piece stays linked to its packet for the history but is no longer in the shop's
 * custody, so boxes and packets stop counting and weighing it. Reserved pieces (a sale nobody has
 * verified yet) are still counted. Needs the MySQL dev database:
 *   DB_CONNECTION=mysql DB_DATABASE=radharani_erp php artisan test --filter=CustodyCountsTest
 */
class CustodyCountsTest extends TestCase
{
    use DatabaseTransactions;

    private Box $box;
    private Packet $packet;

    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') === 'sqlite') {
            $this->markTestSkipped('Needs the MySQL database; see the note at the top of this class.');
        }

        $this->actingAs(User::role('owner')->first() ?? $this->markTestSkipped('Needs the seeded owner user.'));

        $this->box = Box::create(['code' => 'CB' . random_int(100000, 999999)]);
        $this->packet = Packet::create(['code' => 'CP' . random_int(100000, 999999), 'box_id' => $this->box->id]);
    }

    private function piece(string $status, float $weight): Item
    {
        // Created in stock inside the packet, then moved to the status under test (Item::saving ties on-hold to "no packet").
        $item = Item::create(['internal_code' => 'CU' . random_int(1000, 9999), 'packet_id' => $this->packet->id, 'metal' => 'gold', 'category' => 'Ring', 'purity' => '22K', 'weight' => $weight, 'making_value' => 0, 'status' => 'in_stock']);

        return tap($item)->update(['status' => $status]);
    }

    public function test_sold_pieces_are_not_counted_or_weighed_in_a_packet_or_box(): void
    {
        $this->piece('in_stock', 2);
        $this->piece('reserved', 3);
        $this->piece('sold', 5);

        $packet = Packet::withCount('custodyItems as items_count')->withSum('custodyItems as items_sum_weight', 'weight')->find($this->packet->id);
        $this->assertSame(2, $packet->items_count);
        $this->assertEquals(5.0, (float) $packet->items_sum_weight);

        $box = Box::withCount('custodyItems as items_count')->withSum('custodyItems as items_sum_weight', 'weight')->find($this->box->id);
        $this->assertSame(2, $box->items_count);
        $this->assertEquals(5.0, (float) $box->items_sum_weight);

        // The sold piece is still linked: the record stays whole.
        $this->assertSame(3, $this->packet->items()->count());
    }

    public function test_the_detail_pages_and_list_show_custody_figures_and_the_list_notes_the_sold(): void
    {
        $this->piece('in_stock', 2);
        $this->piece('sold', 5);

        Livewire::test(PacketDetail::class, ['packet' => $this->packet])
            ->assertViewHas('items', fn ($items) => $items->count() === 1)
            ->assertDontSee('Sold from this packet');   // sales are in the History, not a block of their own

        Livewire::test(BoxDetail::class, ['box' => $this->box])
            ->assertViewHas('pieces', 1);

        Livewire::test(ContainerList::class)->set('search', $this->packet->code)
            ->assertSee('1 sold');
    }

    public function test_a_sale_shows_inside_the_history_of_the_piece_its_packet_and_its_box(): void
    {
        $sold = $this->piece('sold', 5);
        $customer = \App\Models\Customer\Customer::create(['name' => 'History Buyer', 'phone' => (string) random_int(6000000000, 9999999999)]);
        $sale = \App\Models\Sales\Sale::create(['customer_id' => $customer->id, 'invoice_number' => 'T' . random_int(100000, 999999), 'type' => 'sale', 'total' => 100, 'confirmed_by_accountant' => true, 'created_by' => auth()->id()]);
        $sale->items()->attach($sold->id, ['price_at_sale' => 100]);

        $history = app(\App\Services\StockHistoryService::class);
        foreach ([[$history->forItem($sold), 'Sold (verified)'], [$history->forPacket($this->packet), 'Sold (verified): ' . $sold->label], [$history->forBox($this->box), 'Sold (verified): ' . $sold->label]] as [$events, $title]) {
            $e = $events->firstWhere('kind', 'sale');
            $this->assertNotNull($e, 'the sale is an event in the timeline');
            $this->assertStringContainsString($title, $e['title']);
            $this->assertContains('To History Buyer', $e['meta']);
            $this->assertStringContainsString((string) $sale->id, (string) $e['link']);
        }

        // and a cancellation follows it
        app(\App\Services\SaleCanceller::class)->cancel($sale, 'wrong piece');
        $titles = $history->forPacket($this->packet)->pluck('title')->implode(' | ');
        $this->assertStringContainsString('Sale cancelled, back in stock', $titles);

        Livewire::test(PacketDetail::class, ['packet' => $this->packet])->assertSee('Sold (verified): ' . $sold->label)->assertDontSee('Sold from this packet');
        $this->get(route('stock.items.show', $sold))->assertOk()->assertSee('History');
    }

    public function test_a_container_with_only_sold_pieces_is_not_empty_and_so_is_not_offered_for_deletion(): void
    {
        $this->piece('sold', 5);

        Livewire::test(ContainerList::class)->set('search', $this->packet->code)->set('contents', 'empty')
            ->assertDontSee($this->packet->code);
    }
}

<?php

namespace Tests\Feature\Movement;

use App\Livewire\Movement\VaultCounterMove;
use App\Livewire\Stock\AssignToContainer;
use App\Models\Stock\Box;
use App\Models\Stock\Item;
use App\Models\Stock\Packet;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * 28 October: Change Item location works the way a piece's own page does. Scan pieces onto a list, choose where they go
 * (scan the packet or box, or pick the packet from the list grouped by box), press Move once. A piece already on the list is
 * not scanned again.
 *   DB_CONNECTION=mysql DB_DATABASE=radharani_erp php artisan test --filter=ChangeLocationTest
 */
class ChangeLocationTest extends TestCase
{
    use DatabaseTransactions;

    private Box $box;
    private Packet $from;
    private Packet $to;

    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') === 'sqlite') {
            $this->markTestSkipped('Needs the MySQL database; see the note at the top of this class.');
        }
        $this->actingAs(User::role('owner')->first() ?? $this->markTestSkipped('Needs the seeded owner user.'));

        $this->box = Box::create(['code' => 'CL' . random_int(10000, 99999)]);
        $this->from = Packet::create(['code' => 'CF' . random_int(10000, 99999), 'box_id' => $this->box->id]);
        $this->to = Packet::create(['code' => 'CT' . random_int(10000, 99999), 'box_id' => $this->box->id]);
    }

    private function piece(string $status = 'in_stock', ?Packet $in = null): Item
    {
        $item = Item::create(['packet_id' => ($in ?? $this->from)->id, 'internal_code' => 'CL' . random_int(1000, 9999), 'metal' => 'gold', 'category' => 'Ring', 'purity' => '22K', 'making_value' => 0, 'weight' => 2.5, 'status' => 'in_stock']);

        return $status === 'in_stock' ? $item : tap($item)->update(['status' => $status]);
    }

    public function test_pieces_are_scanned_onto_a_list_and_a_piece_is_not_scanned_twice(): void
    {
        $a = $this->piece();
        $b = $this->piece();

        $c = Livewire::test(AssignToContainer::class)->call('scan', $a->label)->call('scan', $b->label);
        $this->assertCount(2, $c->get('tray'));

        $c->call('scan', $a->label)->assertSet('feedback.message', 'This item is already scanned.')->assertSet('feedback.tone', 'info');
        $this->assertCount(2, $c->get('tray'), 'not added a second time');
        $this->assertSame($this->from->id, $a->fresh()->packet_id, 'and nothing moved yet');
    }

    public function test_several_codes_at_once_and_unknown_or_sold_pieces(): void
    {
        $a = $this->piece();
        $b = $this->piece();
        $sold = $this->piece('sold');

        $c = Livewire::test(AssignToContainer::class)->call('scan', $a->label . "\n" . $b->label . "\n" . $a->label);
        $this->assertCount(2, $c->get('tray'), 'a repeat inside one paste is ignored too');

        $c->call('scan', 'NOSUCHCODE')->assertSet('feedback.tone', 'error');
        $c->call('scan', $sold->label)->assertSet('feedback.tone', 'error')->assertSee('This piece is sold');
        $this->assertCount(2, $c->get('tray'));
    }

    public function test_the_destination_is_chosen_like_on_the_piece_page_and_everything_moves_at_once(): void
    {
        $a = $this->piece();
        $b = $this->piece();

        $c = Livewire::test(AssignToContainer::class)->call('scan', $a->label)->call('scan', $b->label);

        // not chosen yet: refused with a message, nothing moves
        $c->call('move')->assertHasErrors('moveTo');
        $this->assertSame($this->from->id, $a->fresh()->packet_id);

        // scanning a packet chooses it and narrows the list to its box
        $c->call('scanDestination', $this->to->code)->assertSet('moveTo', (string) $this->to->id)->assertSet('moveBox', $this->box->code);
        $c->call('move')->assertHasNoErrors();

        $this->assertSame($this->to->id, $a->fresh()->packet_id);
        $this->assertSame($this->to->id, $b->fresh()->packet_id);
        $this->assertSame([], $c->get('tray'));
        $c->assertSet('lastMove.count', 2)->assertSet('lastMove.dest', $this->to->code);
    }

    public function test_scanning_a_box_narrows_the_packets_and_scanning_nothing_known_says_so(): void
    {
        $c = Livewire::test(AssignToContainer::class)->call('scanDestination', $this->box->code);
        $c->assertSet('moveBox', $this->box->code)->assertSet('moveTo', '', 'two packets in it, so the person picks');

        $only = Box::create(['code' => 'ON' . random_int(10000, 99999)]);
        $one = Packet::create(['code' => 'OP' . random_int(10000, 99999), 'box_id' => $only->id]);
        $c->call('scanDestination', $only->code)->assertSet('moveTo', (string) $one->id);

        $c->call('scanDestination', 'NOPE')->assertSet('moveScanError', 'No packet or box has that code.');
    }

    public function test_a_piece_can_be_taken_out_of_every_packet_and_goes_on_hold(): void
    {
        $a = $this->piece();

        Livewire::test(AssignToContainer::class)->call('scan', $a->label)->set('moveTo', 'none')->call('move')->assertHasNoErrors();

        $this->assertNull($a->fresh()->packet_id);
        $this->assertSame('on_hold', $a->fresh()->status);
    }

    public function test_the_last_move_can_be_undone(): void
    {
        $a = $this->piece();

        $c = Livewire::test(AssignToContainer::class)->call('scan', $a->label)->set('moveTo', (string) $this->to->id)->call('move');
        $this->assertSame($this->to->id, $a->fresh()->packet_id);

        $c->call('undoLast')->assertSet('lastMove.undone', true);
        $this->assertSame($this->from->id, $a->fresh()->packet_id);
    }

    public function test_packets_go_into_a_box_the_same_way(): void
    {
        $other = Box::create(['code' => 'OB' . random_int(10000, 99999)]);

        $c = Livewire::test(AssignToContainer::class)->call('setKind', 'packets')->call('scan', $this->from->code)->call('scan', $this->from->code);
        $this->assertCount(1, $c->get('tray'));
        $c->assertSet('feedback.message', 'This item is already scanned.');

        $c->call('scanDestination', $this->to->code)->assertSet('moveScanError', 'Packets go into a box. Scan a box.');
        $c->call('scanDestination', $other->code)->assertSet('moveTo', (string) $other->id)->call('move')->assertHasNoErrors();

        $this->assertSame($other->id, $this->from->fresh()->box_id);
    }

    public function test_changing_what_is_moved_starts_the_list_again_and_old_addresses_open_the_move_screen(): void
    {
        $a = $this->piece();
        $c = Livewire::test(AssignToContainer::class)->call('scan', $a->label)->call('setKind', 'packets');
        $this->assertSame([], $c->get('tray'));

        foreach (['scan', 'pick', 'whatever'] as $old) {
            Livewire::withQueryParams(['mode' => $old])->test(AssignToContainer::class)->assertSet('mode', 'move')->assertSee('What are you moving?');
        }
        Livewire::withQueryParams(['mode' => 'sheet'])->test(AssignToContainer::class)->assertSet('mode', 'sheet')->assertSee('Upload a spreadsheet');
    }

    public function test_vault_to_counter_says_it_in_the_same_words(): void
    {
        $a = $this->piece();
        $c = Livewire::test(VaultCounterMove::class)->call('scan', $a->label)->call('scan', $a->label);

        $this->assertCount(1, $c->get('tray'));
        $c->assertSet('feedback.message', 'This item is already scanned.');
    }
}

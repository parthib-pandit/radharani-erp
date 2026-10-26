<?php

namespace Tests\Feature\Movement;

use App\Livewire\Admin\LocationManager;
use App\Livewire\Movement\VaultCounterMove;
use App\Livewire\Stock\ContainerList;
use App\Models\Location;
use App\Models\Movement\Movement;
use App\Models\Stock\Box;
use App\Models\Stock\Item;
use App\Models\Stock\Packet;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * 25 October: the shop has several vaults. A box (and a packet not in a box) is kept in one; what goes to the counter
 * comes back to the vault it left, and can be returned to another vault, which is then where it is kept.
 *   DB_CONNECTION=mysql DB_DATABASE=radharani_erp php artisan test --filter=MultipleVaultsTest
 */
class MultipleVaultsTest extends TestCase
{
    use DatabaseTransactions;

    private User $user;
    private Location $vault1;
    private Location $vault2;

    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') === 'sqlite') {
            $this->markTestSkipped('Needs the MySQL database; see the note at the top of this class.');
        }
        $this->user = User::role('owner')->first() ?? $this->markTestSkipped('Needs the seeded owner user.');
        $this->actingAs($this->user);

        $this->vault1 = Location::vault() ?? $this->markTestSkipped('Needs the seeded vault.');
        $this->vault2 = Location::create(['name' => 'Vault Two', 'type' => 'vault', 'sort_order' => 90]);
    }

    private function boxWithPiece(?int $vaultId = null): array
    {
        $box = Box::create(['code' => 'MV' . random_int(10000, 99999), 'home_vault_id' => $vaultId]);
        $packet = Packet::create(['code' => 'MP' . random_int(10000, 99999), 'box_id' => $box->id]);
        $item = Item::create(['packet_id' => $packet->id, 'internal_code' => 'MV' . random_int(100, 999), 'metal' => 'gold', 'category' => 'Ring', 'purity' => '22K', 'making_value' => 0, 'weight' => 3, 'status' => 'in_stock']);

        return [$box, $packet, $item];
    }

    public function test_the_shop_can_have_more_than_one_vault_and_a_tray_sits_in_the_one_chosen(): void
    {
        Livewire::test(LocationManager::class)->call('create')->set('name', 'Vault Three')->set('type', 'vault')->call('save')->assertHasNoErrors();
        $this->assertSame(3, Location::where('type', 'vault')->count());

        Livewire::test(LocationManager::class)->call('create')->set('name', 'Tray B1')->set('type', 'tray')->set('parent_id', $this->vault2->id)->call('save')->assertHasNoErrors();
        $this->assertSame($this->vault2->id, Location::where('name', 'Tray B1')->value('parent_id'));
        $this->assertSame($this->vault2->id, Location::vaultOfPlace(Location::where('name', 'Tray B1')->value('id')));
    }

    public function test_a_vault_can_not_be_switched_off_while_boxes_are_kept_in_it_or_if_it_is_the_last(): void
    {
        [$box] = $this->boxWithPiece($this->vault2->id);

        Livewire::test(LocationManager::class)->call('toggle', $this->vault2->id);
        $this->assertTrue($this->vault2->fresh()->is_active, 'a box is kept there');

        $box->update(['home_vault_id' => $this->vault1->id]);
        Livewire::test(LocationManager::class)->call('toggle', $this->vault2->id);
        $this->assertFalse($this->vault2->fresh()->is_active);
    }

    public function test_where_a_box_is_kept_decides_where_its_pieces_are(): void
    {
        [$box1, , $item1] = $this->boxWithPiece();                       // no vault of its own: the first
        [$box2, , $item2] = $this->boxWithPiece($this->vault2->id);

        $at = Location::itemLocations();
        $this->assertSame($this->vault1->id, $at[$item1->id]);
        $this->assertSame($this->vault2->id, $at[$item2->id]);
        $this->assertSame($this->vault2->id, Location::vaultOf('item', $item2->id));
        $this->assertSame($this->vault2->id, Location::vaultOf('packet', $item2->packet_id), 'a packet follows its box');
    }

    public function test_a_box_returns_to_the_vault_it_was_kept_in(): void
    {
        [$box2] = $this->boxWithPiece($this->vault2->id);
        $counter = Location::defaultFloor();

        $c = Livewire::test(VaultCounterMove::class)->set('locationId', $counter->id)->set('find', '')->call('scan', $box2->code)->call('confirmMove');
        $this->assertSame('vault_out', Movement::where('trackable_type', 'box')->where('trackable_id', $box2->id)->latest('id')->value('movement_type'));

        // Back: nothing chosen, so it goes to the vault it was kept in
        Livewire::test(VaultCounterMove::class)->call('setDirection', 'to_vault')->call('scan', $box2->code)->call('confirmMove');
        $back = Movement::where('trackable_type', 'box')->where('trackable_id', $box2->id)->latest('id')->first();
        $this->assertSame('vault_in', $back->movement_type);
        $this->assertSame($this->vault2->id, (int) $back->location_id);
    }

    public function test_returning_a_box_to_another_vault_moves_where_it_is_kept(): void
    {
        [$box1, , $item1] = $this->boxWithPiece();                       // kept in the first vault
        Livewire::test(VaultCounterMove::class)->set('locationId', Location::defaultFloor()->id)->call('scan', $box1->code)->call('confirmMove');

        Livewire::test(VaultCounterMove::class)->call('setDirection', 'to_vault')->set('vaultTrayId', $this->vault2->id)->call('scan', $box1->code)->call('confirmMove');

        $this->assertSame($this->vault2->id, (int) $box1->fresh()->home_vault_id);
        $this->assertSame($this->vault2->id, Location::itemLocations()[$item1->id], 'its pieces are in the new vault too');
        $this->assertSame($this->vault2->id, (int) Movement::where('trackable_type', 'box')->where('trackable_id', $box1->id)->latest('id')->value('location_id'));
    }

    public function test_a_piece_goes_back_with_its_packet_and_a_tray_belongs_to_its_vault(): void
    {
        [$box, $packet, $item] = $this->boxWithPiece($this->vault2->id);
        $tray = Location::create(['name' => 'Tray V2', 'type' => 'tray', 'parent_id' => $this->vault2->id]);

        Livewire::test(VaultCounterMove::class)->set('locationId', Location::defaultFloor()->id)->call('scan', $item->label)->call('confirmMove');
        Livewire::test(VaultCounterMove::class)->call('setDirection', 'to_vault')->set('vaultTrayId', $this->vault1->id)->call('scan', $item->label)->call('confirmMove');

        // Asked to go to Vault 1, a loose piece still follows its packet into Vault Two.
        $this->assertSame($this->vault2->id, (int) Movement::where('trackable_type', 'item')->where('trackable_id', $item->id)->latest('id')->value('location_id'));
        $this->assertSame($this->vault2->id, (int) $box->fresh()->home_vault_id);

        // On a tray: that tray, and the tray's vault
        Livewire::test(VaultCounterMove::class)->set('locationId', Location::defaultFloor()->id)->call('scan', $item->label)->call('confirmMove');
        Livewire::test(VaultCounterMove::class)->call('setDirection', 'to_vault')->set('vaultTrayId', $tray->id)->call('scan', $item->label)->call('confirmMove');
        $this->assertSame($tray->id, Location::itemLocations()[$item->id]);
    }

    public function test_the_box_form_and_list_offer_the_vault_only_when_there_are_several(): void
    {
        [$box] = $this->boxWithPiece($this->vault2->id);

        Livewire::test(ContainerList::class)->set('search', $box->code)->assertSee('Kept in Vault Two');
        Livewire::test(ContainerList::class)->call('edit', 'box', $box->id)->set('home_vault_id', $this->vault1->id)->call('save')->assertHasNoErrors();
        $this->assertSame($this->vault1->id, (int) $box->fresh()->home_vault_id);

        $this->get(route('stock.boxes.show', $box))->assertOk()->assertSee('Kept in');
    }
}

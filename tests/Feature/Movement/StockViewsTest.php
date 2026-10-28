<?php

namespace Tests\Feature\Movement;

use App\Livewire\Stock\BoxDetail;
use App\Livewire\Stock\CategoryDetail;
use App\Livewire\Stock\HierarchyConfigurator;
use App\Models\Stock\Box;
use App\Models\Stock\Item;
use App\Models\Stock\ItemCategory;
use App\Models\Stock\Packet;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * 28 October: the configurator shows ten at a time, a box's bar says only In stock / Not here, a subcategory has a page
 * listing its pieces and where they are, and a packet can no longer be taken out of a box from the box's page.
 *   DB_CONNECTION=mysql DB_DATABASE=radharani_erp php artisan test --filter=StockViewsTest
 */
class StockViewsTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') === 'sqlite') {
            $this->markTestSkipped('Needs the MySQL database; see the note at the top of this class.');
        }
        $this->actingAs(User::role('owner')->first() ?? $this->markTestSkipped('Needs the seeded owner user.'));
    }

    private function piece(Packet $in, string $status = 'in_stock', ?int $categoryId = null): Item
    {
        return Item::create(['packet_id' => $in->id, 'internal_code' => 'SV' . random_int(1000, 9999), 'metal' => 'gold', 'category' => 'Ring', 'category_id' => $categoryId,
            'purity' => '22K', 'making_value' => 0, 'weight' => 2.5, 'status' => $status]);
    }

    public function test_configurator_shows_ten_then_load_more_then_view_all(): void
    {
        $box = Box::create(['code' => 'ZB' . random_int(10000, 99999)]);
        foreach (range(1, 25) as $n) {
            Packet::create(['code' => sprintf('ZP%s-%02d', $box->id, $n), 'box_id' => $box->id]);
        }

        $c = Livewire::test(HierarchyConfigurator::class)->call('selectBox', (string) $box->id);
        $this->assertCount(10, $c->viewData('packets'));
        $this->assertSame(25, $c->viewData('totals')['packet']);

        $c->call('loadMore', 'packet');
        $this->assertCount(20, $c->viewData('packets'));

        $c->call('viewAll', 'packet');
        $this->assertCount(25, $c->viewData('packets'));

        $c->call('showFewer', 'packet');
        $this->assertCount(10, $c->viewData('packets'));
    }

    public function test_configurator_keeps_the_open_packet_visible_beyond_the_first_ten(): void
    {
        $box = Box::create(['code' => 'ZB' . random_int(10000, 99999)]);
        foreach (range(1, 15) as $n) {
            $last = Packet::create(['code' => sprintf('ZP%s-%02d', $box->id, $n), 'box_id' => $box->id]);
        }

        $c = Livewire::test(HierarchyConfigurator::class)->call('selectBox', (string) $box->id)->call('selectPacket', (string) $last->id);
        $this->assertTrue($c->viewData('packets')->contains('id', $last->id));
    }

    public function test_configurator_search_starts_again_from_ten(): void
    {
        $box = Box::create(['code' => 'ZB' . random_int(10000, 99999)]);
        foreach (range(1, 25) as $n) {
            Packet::create(['code' => sprintf('ZP%s-%02d', $box->id, $n), 'box_id' => $box->id]);
        }

        $c = Livewire::test(HierarchyConfigurator::class)->call('selectBox', (string) $box->id)->call('viewAll', 'packet')->set('packetSearch', "ZP{$box->id}-");
        $this->assertCount(10, $c->viewData('packets'));
    }

    public function test_box_page_has_no_remove_packet_action_and_bar_has_two_groups(): void
    {
        $box = Box::create(['code' => 'ZB' . random_int(10000, 99999)]);
        $packet = Packet::create(['code' => 'ZP' . random_int(10000, 99999), 'box_id' => $box->id]);
        $this->piece($packet);
        $this->piece($packet, 'dispatched');
        $this->piece($packet, 'reserved');

        $c = Livewire::test(BoxDetail::class, ['box' => $box]);
        $c->assertSee('In stock')->assertSee('Not here')->assertDontSee('Take out of this box')->assertDontSee('Pending review');
        $this->assertFalse(method_exists(BoxDetail::class, 'removePacket'));
    }

    public function test_category_page_lists_the_pieces_with_box_packet_and_place(): void
    {
        $category = ItemCategory::create(['metal' => 'gold', 'name' => 'ZCat' . random_int(1000, 9999), 'sort_order' => 99]);
        $box = Box::create(['code' => 'ZB' . random_int(10000, 99999)]);
        $packet = Packet::create(['code' => 'ZP' . random_int(10000, 99999), 'box_id' => $box->id]);
        $a = $this->piece($packet, 'in_stock', $category->id);
        $b = $this->piece($packet, 'dispatched', $category->id);
        $other = $this->piece($packet);

        Livewire::test(CategoryDetail::class, ['category' => $category])
            ->assertSee($a->label)->assertSee($b->label)->assertSee($box->code)->assertSee($packet->code)->assertSee('Out of the shop')
            ->assertDontSee($other->label)
            ->set('search', $a->label)->assertSee($a->label)->assertDontSee($b->label);

        $this->get(route('stock.categories.show', $category))->assertOk()->assertSee($category->name);
    }
}

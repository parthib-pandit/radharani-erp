<?php

namespace Tests\Feature\Movement;

use App\Livewire\Stock\ItemDetail;
use App\Models\Stock\Box;
use App\Models\Stock\Item;
use App\Models\Stock\Packet;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * 29 October: a piece can be tagged with another from its own page (earrings, or a set of any size), and untagged again.
 *   DB_CONNECTION=mysql DB_DATABASE=radharani_erp php artisan test --filter=PairTaggingTest
 */
class PairTaggingTest extends TestCase
{
    use DatabaseTransactions;

    private Packet $packet;

    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') === 'sqlite') {
            $this->markTestSkipped('Needs the MySQL database; see the note at the top of this class.');
        }
        $this->actingAs(User::role('owner')->first() ?? $this->markTestSkipped('Needs the seeded owner user.'));

        $box = Box::create(['code' => 'PB' . random_int(10000, 99999)]);
        $this->packet = Packet::create(['code' => 'PP' . random_int(10000, 99999), 'box_id' => $box->id]);
    }

    private function piece(string $category = 'ZEar', string $status = 'in_stock'): Item
    {
        return Item::create(['packet_id' => $this->packet->id, 'internal_code' => 'PT' . random_int(1000, 9999), 'metal' => 'gold', 'category' => $category,
            'purity' => '22K', 'making_value' => 0, 'weight' => 2.5, 'status' => $status]);
    }

    public function test_tagging_from_the_piece_page_links_both_pieces(): void
    {
        $a = $this->piece();
        $b = $this->piece();

        Livewire::test(ItemDetail::class, ['item' => $a])->call('openPair')->assertSee($b->label)->call('pairWith', $b->id)->assertSet('showPair', false);

        $this->assertNotNull($a->fresh()->pair_group_id);
        $this->assertSame($a->fresh()->pair_group_id, $b->fresh()->pair_group_id);
        $this->assertSame([$b->id], $a->fresh()->mates()->pluck('id')->all());

        // Shown on both pages, and in the history.
        $this->get(route('stock.items.show', $b))->assertOk()->assertSee($a->label)->assertSee('Pair');
        $this->assertTrue(\Spatie\Activitylog\Models\Activity::where('subject_type', Item::class)->where('subject_id', $b->id)->exists());
    }

    public function test_a_third_piece_joins_the_set_and_unpairing_one_keeps_the_rest_together(): void
    {
        [$a, $b, $c] = [$this->piece(), $this->piece(), $this->piece()];
        $a->tagWith($b);
        $c->tagWith($a->fresh());

        $this->assertCount(2, $a->fresh()->mates());
        $this->assertSame($a->fresh()->pair_group_id, $c->fresh()->pair_group_id);

        Livewire::test(ItemDetail::class, ['item' => $c->fresh()])->call('unpair');
        $this->assertNull($c->fresh()->pair_group_id);
        $this->assertSame($a->fresh()->pair_group_id, $b->fresh()->pair_group_id);
        $this->assertNotNull($a->fresh()->pair_group_id);

        // Unpair the second last one: the group dissolves, nobody is left "paired" alone.
        $b->fresh()->untag();
        $this->assertNull($a->fresh()->pair_group_id);
        $this->assertNull($b->fresh()->pair_group_id);
    }

    public function test_two_pieces_in_different_sets_are_refused_until_one_is_unpaired(): void
    {
        [$a, $b, $c, $d] = [$this->piece(), $this->piece(), $this->piece(), $this->piece()];
        $a->tagWith($b);
        $c->tagWith($d);

        Livewire::test(ItemDetail::class, ['item' => $a->fresh()])->call('pairWith', $c->id);
        $this->assertNotSame($a->fresh()->pair_group_id, $c->fresh()->pair_group_id);
    }

    public function test_sold_pieces_and_itself_are_not_offered_or_taggable(): void
    {
        $a = $this->piece();
        $sold = $this->piece('ZEar', 'sold');

        $c = Livewire::test(ItemDetail::class, ['item' => $a])->call('openPair');
        $this->assertFalse($c->viewData('pairCandidates')->contains('id', $sold->id));
        $this->assertFalse($c->viewData('pairCandidates')->contains('id', $a->id));

        $c->call('pairWith', $sold->id)->call('pairWith', $a->id);
        $this->assertNull($a->fresh()->pair_group_id);
        $this->assertNull($sold->fresh()->pair_group_id);
    }

    public function test_search_finds_a_piece_of_another_category_by_its_code(): void
    {
        $a = $this->piece('ZEar');
        $other = $this->piece('ZOther');

        $c = Livewire::test(ItemDetail::class, ['item' => $a])->call('openPair');
        $this->assertFalse($c->viewData('pairCandidates')->contains('id', $other->id)); // nothing typed: same category only
        $c->set('pairSearch', $other->label);
        $this->assertTrue($c->viewData('pairCandidates')->contains('id', $other->id));
    }
}

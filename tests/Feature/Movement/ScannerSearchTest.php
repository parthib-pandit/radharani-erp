<?php

namespace Tests\Feature\Movement;

use App\Models\Stock\Box;
use App\Models\Stock\Item;
use App\Models\Stock\Packet;
use App\Models\User;
use App\Support\ScanSearch;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * 25 October: the camera scanner opens full screen and has a Search that finds pieces, packets and boxes by HUID,
 * code, category or name, and hands the result back as the code to use, exactly like a scan.
 *   DB_CONNECTION=mysql DB_DATABASE=radharani_erp php artisan test --filter=ScannerSearchTest
 */
class ScannerSearchTest extends TestCase
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
    }

    private function piece(array $extra = []): Item
    {
        return Item::create($extra + ['packet_id' => $this->packetId(), 'internal_code' => 'SS' . random_int(100, 999), 'metal' => 'gold', 'category' => 'Zorbangle', 'purity' => '22K', 'making_value' => 0, 'weight' => 4.5, 'status' => 'in_stock']);
    }

    public function test_it_finds_pieces_by_code_or_category_and_hands_back_the_code_to_use(): void
    {
        $item = $this->piece(['huid_code' => 'QZ9R4K', 'internal_code' => null]);

        $byCode = ScanSearch::run('QZ9R');
        $this->assertSame('item', $byCode[0]['type']);
        $this->assertSame('QZ9R4K', $byCode[0]['code'], 'the HUID is the code a field gets');
        $this->assertStringContainsString('Zorbangle', $byCode[0]['detail']);

        $this->assertContains('QZ9R4K', array_column(ScanSearch::run('zorbangle'), 'code'));

        // A piece with no HUID hands back its internal code.
        $plain = $this->piece(['internal_code' => 'QQ7H2']);
        $this->assertContains('QQ7H2', array_column(ScanSearch::run('QQ7H'), 'code'));
    }

    public function test_it_finds_packets_and_boxes_by_code_or_label(): void
    {
        Box::create(['code' => 'BOXZQ-1', 'label' => 'Gents rings']);
        Packet::create(['code' => 'PKTZQ-1', 'label' => 'Ladies chains']);

        $results = collect(ScanSearch::run('ZQ-1'));
        $this->assertSame('box', $results->firstWhere('code', 'BOXZQ-1')['type']);
        $this->assertSame('packet', $results->firstWhere('code', 'PKTZQ-1')['type']);
        $this->assertSame('PKTZQ-1', collect(ScanSearch::run('ladies chains'))->firstWhere('type', 'packet')['code'], 'by label too');
    }

    public function test_sold_pieces_are_left_out_and_a_short_search_finds_nothing(): void
    {
        $this->piece(['huid_code' => 'SOLD99', 'internal_code' => null, 'status' => 'sold']);

        $this->assertNotContains('SOLD99', array_column(ScanSearch::run('SOLD99'), 'code'));
        $this->assertSame([], ScanSearch::run('S'));
        $this->assertSame([], ScanSearch::run('   '));
    }

    public function test_the_endpoint_answers_in_json_and_needs_a_signed_in_user(): void
    {
        $this->piece(['huid_code' => 'EP4TX7', 'internal_code' => null]);

        $this->getJson(route('stock.scan.search', ['q' => 'EP4T']))->assertOk()->assertJsonPath('results.0.code', 'EP4TX7');

        auth()->logout();
        $this->getJson(route('stock.scan.search', ['q' => 'EP4T']))->assertUnauthorized();
    }

    public function test_staff_without_the_stock_permission_can_still_use_the_scanner_search(): void
    {
        $staff = User::whereDoesntHave('permissions')->get()->first(fn ($u) => ! $u->can('stock.manage') && $u->is_active);
        if (! $staff) {
            $this->markTestSkipped('Needs an active user without stock.manage.');
        }
        $this->piece(['huid_code' => 'NP3QK8', 'internal_code' => null]);

        $this->actingAs($staff)->getJson(route('stock.scan.search', ['q' => 'NP3Q']))->assertOk()->assertJsonPath('results.0.code', 'NP3QK8');
    }

    public function test_the_scanner_is_full_screen_with_a_search_and_a_done_count(): void
    {
        $html = $this->get(route('stock.items'))->assertOk()->getContent();

        $this->assertStringContainsString('fixed inset-0', $html);
        $this->assertStringContainsString('Search by HUID, code or name', $html);
        $this->assertMatchesRegularExpression('~stock(\\\\?/)scan(\\\\?/)search~', $html, 'the scanner knows where to search');
        $this->assertStringContainsString('scanned', $html);
    }
}

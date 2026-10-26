<?php

namespace Tests\Feature\Movement;

use App\Livewire\Stock\BulkImport;
use App\Models\Stock\Item;
use App\Models\Stock\ReservedCode;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * 25 October: the import sheet comes with its HUID column filled with internal codes. They are reserved when the sheet
 * is downloaded, so no two sheets or pieces share one, and a reserved code in the HUID column imports as the piece's
 * internal code.
 *   DB_CONNECTION=mysql DB_DATABASE=radharani_erp php artisan test --filter=ReservedCodesTest
 */
class ReservedCodesTest extends TestCase
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

    private function review(array $rows)
    {
        return Livewire::test(BulkImport::class)
            ->set('headers', ['S No.', 'Item Category', 'No Of Unit', 'HUID', 'Weight of Article (Gms)', 'Purity'])
            ->set('rows', $rows)
            ->set('mapping', ['', 'category', '', 'huid_code', 'weight', 'purity'])
            ->set('defaultMetal', 'gold')->set('defaultMakingType', 'flat_per_piece')->set('defaultMakingValue', 0)
            ->call('confirmMapping');
    }

    public function test_reserving_gives_unique_five_character_codes_that_no_piece_has(): void
    {
        $taken = Item::create(['internal_code' => 'RC' . random_int(100, 999), 'metal' => 'gold', 'category' => 'Ring', 'purity' => '22K', 'weight' => 1, 'making_value' => 0, 'status' => 'on_hold']);

        $codes = ReservedCode::reserve(40);

        $this->assertCount(40, $codes);
        $this->assertCount(40, $codes->unique());
        $this->assertTrue($codes->every(fn ($c) => strlen($c) === 5 && preg_match('/^[' . Item::CODE_ALPHABET . ']+$/', $c)));
        $this->assertNotContains($taken->internal_code, $codes->all());
        $this->assertSame(40, ReservedCode::whereIn('code', $codes->all())->count());
        $this->assertTrue(ReservedCode::isTaken($codes->first()));
        $this->assertSame($this->user->id, ReservedCode::where('code', $codes->first())->value('user_id'));
    }

    public function test_the_blank_sheet_download_reserves_a_code_for_every_row(): void
    {
        $before = ReservedCode::count();

        Livewire::test(BulkImport::class)->set('templateCount', 7)->call('downloadBlankTemplate')->assertFileDownloaded('import-with-codes-7-rows.csv');
        $this->assertSame($before + 7, ReservedCode::count());

        // Unticked: the old blank sheet, nothing reserved.
        Livewire::test(BulkImport::class)->set('templateCount', 3)->set('withCodes', false)->call('downloadBlankTemplate')->assertFileDownloaded('huid-import-3-rows.csv');
        $this->assertSame($before + 7, ReservedCode::count());
    }

    public function test_a_reserved_code_imports_as_the_internal_code_and_is_marked_used(): void
    {
        [$a, $b, $unused] = ReservedCode::reserve(3)->all();

        $c = $this->review([['1', 'Mix Ornaments', '1', $a, '3.250', '22K'], ['2', 'Mix Ornaments', '1', $b, '4.100', ''], ['3', 'Mix Ornaments', '1', $unused, '', '']]);

        $rows = $c->get('reviewRows');
        $this->assertCount(2, $rows, 'the row left without a weight is skipped');
        $this->assertSame($a, $rows[0]['data']['internal_code']);
        $this->assertNull($rows[0]['data']['huid_code']);
        $this->assertEmpty($rows[0]['errors']);

        $c->call('confirmImport');

        $item = Item::where('internal_code', $a)->firstOrFail();
        $this->assertNull($item->huid_code);
        $this->assertSame('on_hold', $item->status);
        $this->assertEquals(3.25, (float) $item->weight);
        $this->assertSame($item->id, (int) ReservedCode::where('code', $a)->value('used_item_id'));
        $this->assertNull(ReservedCode::where('code', $unused)->value('used_item_id'), 'an unused code stays free');
    }

    public function test_the_same_sheet_imported_twice_flags_the_codes_already_used(): void
    {
        [$a] = ReservedCode::reserve(1)->all();
        $this->review([['1', 'Mix Ornaments', '1', $a, '3.250', '22K']])->call('confirmImport');

        $rows = $this->review([['1', 'Mix Ornaments', '1', $a, '3.250', '22K']])->get('reviewRows');

        $this->assertNotEmpty($rows[0]['duplicate']);
        $this->assertFalse($rows[0]['include'], 'a duplicate starts unticked');
    }

    public function test_a_real_huid_still_imports_as_a_huid(): void
    {
        $rows = $this->review([['1', 'Ring', '1', 'AB12CD', '3.250', '22K']])->get('reviewRows');

        $this->assertSame('AB12CD', $rows[0]['data']['huid_code']);
        $this->assertNull($rows[0]['data']['internal_code']);
    }

    public function test_fifty_pieces_gives_a_csv_of_fifty_rows_each_with_its_own_code(): void
    {
        $c = Livewire::test(BulkImport::class)->set('templateCount', 50)->call('downloadBlankTemplate');

        $csv = base64_decode($c->effects['download']['content']);
        $lines = array_values(array_filter(explode("\n", str_replace("\r", '', $csv))));
        $header = str_getcsv(array_shift($lines));
        $this->assertSame('HUID', $header[3]);
        $this->assertCount(50, $lines);

        $codes = array_map(fn ($l) => str_getcsv($l)[3], $lines);
        $this->assertCount(50, array_unique($codes), 'every row has a different code');
        $this->assertTrue(collect($codes)->every(fn ($code) => strlen($code) === 5 && ReservedCode::isTaken($code)), 'and every code is reserved');
    }
}

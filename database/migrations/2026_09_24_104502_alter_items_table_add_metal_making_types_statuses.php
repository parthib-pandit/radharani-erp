<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Confirmed requirements this covers (Radharani context pack, post-clarification):
// - #10 making charges have three types, not two: percentage of rate×weight,
//   flat value per piece, flat value per gram.
// - #9 items returning from karigar/hallmarking sit in a "pending" status
//   until admin confirms — new 'pending_review' status.
// - #12 a sale only becomes final once admin verifies it; the item is held
//   with a 'reserved' status between entry and verification.
// - #18 stock filtering must support metal type — items didn't have an
//   explicit metal column (it was implied inside the free-text `purity`
//   string), so it's split out here.
// (The source_karigar_batch_id / source_purchase_item_id link columns are
// added in a later migration, once those tables exist.)
return new class extends Migration {
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->enum('metal', ['gold', 'silver', 'titanium', 'platinum'])->nullable()->after('packet_id');
        });

        // Widen making_type enum to the 3 confirmed types, keeping the old
        // value valid during the data migration step below.
        DB::statement("ALTER TABLE items MODIFY making_type ENUM('per_piece','percentage','flat_per_piece','flat_per_gram') NOT NULL");
        DB::table('items')->where('making_type', 'per_piece')->update(['making_type' => 'flat_per_piece']);
        DB::statement("ALTER TABLE items MODIFY making_type ENUM('percentage','flat_per_piece','flat_per_gram') NOT NULL");

        DB::statement("ALTER TABLE items MODIFY status ENUM('in_stock','dispatched','sold','pending_review','reserved') NOT NULL DEFAULT 'in_stock'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE items MODIFY status ENUM('in_stock','dispatched','sold') NOT NULL DEFAULT 'in_stock'");

        DB::statement("ALTER TABLE items MODIFY making_type ENUM('per_piece','percentage','flat_per_piece','flat_per_gram') NOT NULL");
        DB::table('items')->where('making_type', 'flat_per_piece')->update(['making_type' => 'per_piece']);
        DB::statement("ALTER TABLE items MODIFY making_type ENUM('per_piece','percentage') NOT NULL");

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('metal');
        });
    }
};

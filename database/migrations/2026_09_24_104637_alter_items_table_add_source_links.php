<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Split out from the earlier items alteration because these two FKs need
// karigar_raw_batches and purchase_items to already exist. Links a
// newly-created item back to whichever raw source produced it:
// - source_karigar_batch_id: item was created when a karigar raw-material
//   dispatch (#5 sub-flow c) was returned as finished goods.
// - source_purchase_item_id: item was tagged from a raw-material purchase
//   line (#13) that had no item at entry time.
// Both nullable — most items (bought/made the normal way) have neither.
return new class extends Migration {
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('source_karigar_batch_id')->nullable()->after('pair_group_id')
                ->constrained('karigar_raw_batches')->nullOnDelete();
            $table->foreignId('source_purchase_item_id')->nullable()->after('source_karigar_batch_id')
                ->constrained('purchase_items')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('source_purchase_item_id');
            $table->dropConstrainedForeignId('source_karigar_batch_id');
        });
    }
};

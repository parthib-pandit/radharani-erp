<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// #13 Raw-material purchases (untagged) have no item to attach a line to at
// entry time — the previous schema forced every purchase_items row to
// reference a real items.id via a composite (purchase_id, item_id) primary
// key, so there was nothing to attach and no way to note "still needs
// tagging". This gives every line its own id, makes item_id nullable, and
// adds the raw-material description fields + a tag_pending flag. Once the
// item is tagged in Stock (Item::source_purchase_item_id, added in a later
// migration), the line's item_id is filled in — that's a purchase_items
// update, not a purchases row update, so rule 1 (never update purchases)
// still holds.
return new class extends Migration {
    public function up(): void
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            // Both FKs rest on the composite primary key's index — drop
            // them before the primary key itself, then re-add after.
            $table->dropForeign('purchase_items_item_id_foreign');
            $table->dropForeign('purchase_items_purchase_id_foreign');
        });

        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropPrimary(['purchase_id', 'item_id']);
        });

        Schema::table('purchase_items', function (Blueprint $table) {
            $table->id()->first();
            $table->string('description', 150)->nullable()->after('item_id');
            $table->string('category', 50)->nullable()->after('description');
            $table->string('metal', 20)->nullable()->after('category');
            $table->string('purity', 10)->nullable()->after('metal');
            $table->boolean('tag_pending')->default(false)->after('purity');
        });

        DB::statement('ALTER TABLE purchase_items MODIFY item_id BIGINT UNSIGNED NULL');

        Schema::table('purchase_items', function (Blueprint $table) {
            $table->foreign('purchase_id')->references('id')->on('purchases')->cascadeOnDelete();
            $table->foreign('item_id')->references('id')->on('items')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropForeign(['purchase_id']);
        });

        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropColumn(['description', 'category', 'metal', 'purity', 'tag_pending']);
            $table->dropColumn('id');
        });

        DB::statement('ALTER TABLE purchase_items MODIFY item_id BIGINT UNSIGNED NOT NULL');

        Schema::table('purchase_items', function (Blueprint $table) {
            $table->primary(['purchase_id', 'item_id']);
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('purchase_id')->references('id')->on('purchases')->cascadeOnDelete();
        });
    }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// #13 Purchases are admin-only and split into two types: finished product
// (possibly untagged, tagged later) and raw material (untagged). The UI
// already had a purchaseType toggle with nowhere to persist it — this is
// that column.
return new class extends Migration {
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->enum('type', ['finished_product', 'raw_material'])->default('finished_product')->after('vendor_id');
        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};

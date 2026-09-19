<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sale_items', function (Blueprint $table) {
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items');
            $table->decimal('price_at_sale', 10, 2);
            $table->primary(['sale_id', 'item_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('sale_items'); }
};

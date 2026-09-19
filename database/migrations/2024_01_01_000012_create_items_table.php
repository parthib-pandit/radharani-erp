<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('packet_id')->nullable()->constrained('packets')->nullOnDelete();
            $table->string('huid_code', 20)->nullable()->index();
            $table->string('internal_code', 10)->nullable()->index();
            $table->string('category', 50);
            $table->string('purity', 10);
            $table->decimal('weight', 8, 3);
            $table->string('description', 100)->nullable();
            $table->string('hsn_code', 10)->nullable();
            $table->enum('making_type', ['per_piece', 'percentage']);
            $table->decimal('making_value', 10, 2);
            $table->unsignedBigInteger('pair_group_id')->nullable();
            $table->enum('status', ['in_stock', 'dispatched', 'sold'])->default('in_stock')->index();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('items'); }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('discount_rules', function (Blueprint $table) {
            $table->id();
            $table->enum('scope', ['item', 'category', 'box', 'packet', 'weight_tier']);
            $table->unsignedBigInteger('scope_ref_id')->nullable(); // item/box/packet id, null for weight_tier/category
            $table->string('category', 50)->nullable();             // used when scope = category
            $table->decimal('min_weight', 8, 3)->nullable();         // used when scope = weight_tier
            $table->decimal('max_weight', 8, 3)->nullable();
            $table->enum('discount_type', ['flat', 'percentage']);
            $table->decimal('value', 10, 2);
            $table->boolean('active')->default(true);
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index(['scope', 'scope_ref_id']);
            $table->index(['active', 'valid_from', 'valid_to']);
        });
    }
    public function down(): void { Schema::dropIfExists('discount_rules'); }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->enum('trackable_type', ['item', 'packet', 'box']);
            $table->unsignedBigInteger('trackable_id');
            $table->enum('movement_type', [
                'vault_out', 'vault_in',
                'karigar_out', 'karigar_in',
                'hallmark_out', 'hallmark_in',
                'photo_out', 'photo_in',
                'custom_out', 'custom_in',
                'melt_out', 'melt_in',
                'correction',
            ]);
            $table->string('purpose_label', 50)->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->string('counterparty', 100)->nullable();
            $table->date('expected_return')->nullable();
            $table->date('actual_return')->nullable();
            $table->decimal('weight_at_dispatch', 8, 3)->nullable();
            $table->string('photo_path', 255)->nullable();
            $table->string('bill_path', 255)->nullable();
            $table->string('note', 255)->nullable();
            $table->foreignId('reverses_movement_id')->nullable()->constrained('movements')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['trackable_type', 'trackable_id', 'created_at']);
            $table->index(['movement_type', 'created_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('movements'); }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Refinery round-trip: accumulated old gold/silver scrap sent out, refined
// material (with a purity reading) comes back. Confirmed to reuse the same
// simple out/in batch shape as karigar raw material, but kept as its own
// table since the fields genuinely differ (a refined-purity readback, not
// new finished items).
return new class extends Migration {
    public function up(): void
    {
        Schema::create('refinery_batches', function (Blueprint $table) {
            $table->id();
            $table->decimal('weight', 8, 3);
            $table->string('photo_path', 255)->nullable();
            $table->enum('status', ['sent', 'returned'])->default('sent')->index();
            $table->decimal('refined_weight', 8, 3)->nullable();
            $table->decimal('refined_purity', 5, 2)->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refinery_batches');
    }
};

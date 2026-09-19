<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('phone', 15)->index();
            $table->string('address', 255)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('gstin', 15)->nullable();
            $table->decimal('balance', 10, 2)->default(0);
            $table->enum('status', ['past_customer', 'order_given', 'order_pending'])->default('past_customer');
            $table->integer('loyalty_points')->default(0);
            $table->string('referral_code', 10)->unique()->nullable();
            $table->foreignId('referred_by')->nullable()->constrained('customers')->nullOnDelete();
            $table->boolean('imported_from_tally')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('customers'); }
};

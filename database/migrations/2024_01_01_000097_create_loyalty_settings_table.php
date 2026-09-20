<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('loyalty_settings', function (Blueprint $table) {
            $table->id();
            // Single-row config table — enforced in the model, not the DB,
            // since MySQL has no clean "max 1 row" constraint.
            $table->decimal('points_per_rupee', 8, 5)->default(0.001); // e.g. 1 point per ₹1,000
            $table->unsignedInteger('referral_bonus_points')->default(100);
            $table->unsignedInteger('min_redeemable_points')->default(0);
            $table->decimal('point_value_in_rupees', 8, 4)->default(1); // ₹ value of 1 point at redemption
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('loyalty_settings'); }
};

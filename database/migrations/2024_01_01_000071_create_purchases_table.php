<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->string('invoice_number', 50)->nullable();
            $table->decimal('total_weight', 8, 3)->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('gst', 10, 2)->nullable();
            $table->enum('payment_status', ['paid', 'partial', 'pending'])->default('pending');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('purchases'); }
};

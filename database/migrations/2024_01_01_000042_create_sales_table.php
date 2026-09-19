<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('invoice_number', 50)->unique();
            $table->enum('type', ['sale', 'order_delivery']);
            $table->decimal('cgst', 10, 2)->default(0);
            $table->decimal('sgst', 10, 2)->default(0);
            $table->decimal('igst', 10, 2)->default(0);
            $table->json('additional_charges')->nullable();
            $table->decimal('discount', 10, 2)->default(0);
            $table->json('payment_modes')->nullable();
            $table->string('accountant_note', 255)->nullable();
            $table->decimal('total', 10, 2);
            $table->boolean('confirmed_by_accountant')->default(false);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['customer_id', 'created_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('sales'); }
};

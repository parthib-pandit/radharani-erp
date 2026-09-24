<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// #11 Custom Orders are a distinct lifecycle from Sales, not a sale
// sub-type — a pre-commitment that later converts into a sale, with its
// own pipeline (placed → confirmed → ready → delivered → cancelled).
// Rate-locking: if the customer pays the full value at order time, the
// rate is locked to that date (locked_rate/locked_at); otherwise the rate
// at delivery applies (rate_locked stays false, nothing is frozen). Orders
// can be placed on products not currently in stock (out_of_stock flag,
// checkbox at entry) or against a real item already in stock
// (in_stock_item_id).
return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('product_description', 200);
            $table->string('category', 50)->nullable();
            $table->enum('metal', ['gold', 'silver', 'titanium', 'platinum'])->nullable();
            $table->decimal('estimated_weight', 8, 3)->nullable();
            $table->decimal('estimated_value', 10, 2);
            $table->decimal('advance_amount', 10, 2)->default(0);
            $table->boolean('full_payment_now')->default(false);
            $table->boolean('rate_locked')->default(false);
            $table->decimal('locked_rate', 10, 2)->nullable();
            $table->timestamp('locked_at')->nullable();
            $table->foreignId('in_stock_item_id')->nullable()->constrained('items')->nullOnDelete();
            $table->boolean('out_of_stock')->default(false);
            $table->enum('status', ['placed', 'confirmed', 'ready', 'delivered', 'cancelled'])->default('placed')->index();
            $table->date('expected_ready_date')->nullable();
            $table->foreignId('converted_sale_id')->nullable()->constrained('sales')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

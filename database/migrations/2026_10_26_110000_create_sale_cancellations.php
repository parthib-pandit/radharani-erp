<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // 25 Oct feedback: a sale can be cancelled, before or after an admin verifies it. A sales row is never updated (rule 1),
    // so the cancellation is a row of its own pointing at the sale. Insert-only, one per sale.
    public function up(): void
    {
        Schema::create('sale_cancellations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->unique()->constrained('sales');
            $table->string('reason', 255);
            $table->boolean('was_verified')->default(false);
            $table->decimal('held_on_account', 12, 2)->default(0); // what the customer had paid in cash, UPI and so on, now held as an advance
            $table->decimal('credit_returned', 12, 2)->default(0); // exchange credit and advance the sale had used, given back
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_cancellations');
    }
};

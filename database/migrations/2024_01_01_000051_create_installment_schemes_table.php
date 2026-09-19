<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('installment_schemes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->decimal('monthly_amount', 10, 2);
            $table->unsignedTinyInteger('months_paid')->default(0);
            $table->date('start_date');
            $table->enum('status', ['active', 'completed', 'defaulted'])->default('active');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('installment_schemes'); }
};

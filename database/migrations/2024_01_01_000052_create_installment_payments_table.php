<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('installment_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheme_id')->constrained('installment_schemes');
            $table->decimal('amount', 10, 2);
            $table->date('paid_on');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('installment_payments'); }
};

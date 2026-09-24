<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Old Gold/Silver Exchange — the client's exact 4-step process, confirmed
// no step should be abstracted or combined: gross weight → net weight
// after melt → two independent purity readings (auto-averaged) → preset
// deduction → final valuation. `stage` tracks where a given exchange sits
// (received/melted/tested/valued/settled) for the Status Tracker screen.
return new class extends Migration {
    public function up(): void
    {
        Schema::create('exchange_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->decimal('gross_weight', 8, 3);
            $table->string('description', 150)->nullable();
            $table->decimal('net_weight', 8, 3)->nullable();
            $table->decimal('purity_test_1', 5, 2)->nullable();
            $table->decimal('purity_test_2', 5, 2)->nullable();
            $table->decimal('purity_averaged', 5, 2)->nullable();
            $table->decimal('preset_deduction_percent', 5, 2)->default(2.00);
            $table->decimal('deductable_weight', 8, 3)->nullable();
            $table->enum('stage', ['received', 'melted', 'tested', 'valued', 'settled'])->default('received')->index();
            $table->decimal('final_value', 10, 2)->nullable();
            $table->foreignId('settled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('settled_at')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exchange_transactions');
    }
};

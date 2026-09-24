<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// #5 (sub-flow b) A customer's own untagged gold/silver sent to a karigar
// for repair. This is never shop stock — it has no item ID — so it can't
// go through the polymorphic `movements` table (which tracks items/
// packets/boxes only) or `karigar_raw_batches` (shop-owned raw material).
// Tracked against the customer directly instead.
return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer_material_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->string('description', 150);
            $table->decimal('weight_out', 8, 3);
            $table->enum('metal', ['gold', 'silver', 'titanium', 'platinum']);
            $table->date('expected_return')->nullable();
            $table->date('actual_return')->nullable();
            $table->decimal('weight_in', 8, 3)->nullable();
            $table->decimal('weight_loss', 8, 3)->nullable();
            $table->enum('status', ['out', 'returned'])->default('out')->index();
            $table->string('note', 255)->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_material_jobs');
    }
};

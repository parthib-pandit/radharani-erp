<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// #5 (sub-flow c) Karigar raw-material dispatch: raw metal is issued to a
// karigar and finished (untagged) product comes back — the out and the in
// are not the same physical thing, and one dispatch can return as more
// than one finished piece. Gets its own table rather than living in the
// polymorphic `movements` pattern, per the design decision this was
// flagged for ("what leaves and what returns are not the same physical
// thing"). Each resulting finished item links back via
// items.source_karigar_batch_id (added once this table exists).
return new class extends Migration {
    public function up(): void
    {
        Schema::create('karigar_raw_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->decimal('weight_out', 8, 3);
            $table->enum('metal', ['gold', 'silver', 'titanium', 'platinum']);
            $table->string('purity', 10)->nullable();
            $table->string('purpose_label', 50)->nullable();
            $table->date('expected_return')->nullable();
            $table->date('actual_return')->nullable();
            $table->enum('status', ['dispatched', 'partially_returned', 'returned'])->default('dispatched')->index();
            $table->string('note', 255)->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karigar_raw_batches');
    }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rate_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('metal', ['gold', 'silver']);
            $table->decimal('rate', 10, 2);
            $table->enum('source', ['manual', 'api'])->default('manual');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['metal', 'created_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('rate_logs'); }
};

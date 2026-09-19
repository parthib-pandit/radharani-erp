<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->enum('target_type', ['item', 'packet', 'box']);
            $table->unsignedBigInteger('target_id');
            $table->string('code', 20);
            $table->string('file_path', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['target_type', 'target_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('qr_codes'); }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('packets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('box_id')->nullable()->constrained('boxes')->nullOnDelete();
            $table->string('code', 50)->unique();
            $table->string('label', 100)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('packets'); }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->enum('type', ['karigar', 'supplier', 'hallmark_center']);
            $table->string('phone', 15)->nullable();
            $table->string('address', 255)->nullable();
            $table->decimal('balance', 10, 2)->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('vendors'); }
};

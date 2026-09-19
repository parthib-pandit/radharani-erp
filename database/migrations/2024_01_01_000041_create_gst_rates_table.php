<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('gst_rates', function (Blueprint $table) {
            $table->id();
            $table->string('category', 50);
            $table->decimal('rate_percent', 5, 2);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('gst_rates'); }
};

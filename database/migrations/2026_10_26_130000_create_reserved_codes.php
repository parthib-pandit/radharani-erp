<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // 25 Oct feedback: the import sheet comes with its HUID column already filled with internal codes. The codes are
    // reserved the moment the sheet is downloaded, so no two sheets (or pieces) can ever share one. A reserved code
    // that is never imported simply stays unused.
    public function up(): void
    {
        Schema::create('reserved_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('created_at')->useCurrent();
            $table->unsignedBigInteger('used_item_id')->nullable()->index();
            $table->timestamp('used_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reserved_codes');
    }
};

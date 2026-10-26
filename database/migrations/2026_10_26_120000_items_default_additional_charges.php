<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // 25 Oct feedback: a piece can carry additional charges by default (Sakha 250, Polish 100...), each a name and an
    // amount, entered by hand like the making value. A sale starts with them on the bill and staff can change them.
    // They are added after the GST and are not taxed, like every additional charge.
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->json('default_additional_charges')->nullable()->after('stone_value');
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('default_additional_charges');
        });
    }
};

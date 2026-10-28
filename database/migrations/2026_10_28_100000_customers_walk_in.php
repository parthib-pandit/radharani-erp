<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // 28 Oct feedback: a default "Walk-in customer" staff pick with one tap when a buyer gives no details. One shared
    // record with its own ledger (the customer page works for it as for anyone). It has no phone to log in with, so the
    // phone is a word that no real number can match, and no password.
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('is_walk_in')->default(false)->after('imported_from_tally');
        });

        if (! DB::table('customers')->where('is_walk_in', true)->exists()) {
            DB::table('customers')->insert([
                'name' => 'Walk-in customer', 'phone' => 'walk-in', 'status' => 'past_customer', 'is_walk_in' => true,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('is_walk_in');
        });
    }
};

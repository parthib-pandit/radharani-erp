<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // 25 Oct feedback: the shop has several vaults. A box (and a packet that is not in a box) is kept in one of them, so
    // what goes to the counter comes back to the vault it left. Empty means the first vault, where everything started.
    public function up(): void
    {
        Schema::table('boxes', function (Blueprint $table) {
            $table->foreignId('home_vault_id')->nullable()->after('label')->constrained('locations');
        });
        Schema::table('packets', function (Blueprint $table) {
            $table->foreignId('home_vault_id')->nullable()->after('label')->constrained('locations');
        });
    }

    public function down(): void
    {
        Schema::table('packets', function (Blueprint $table) {
            $table->dropConstrainedForeignId('home_vault_id');
        });
        Schema::table('boxes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('home_vault_id');
        });
    }
};

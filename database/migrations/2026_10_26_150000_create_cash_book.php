<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    // 25 Oct feedback: a cash book of two books, Petty cash and Main cash (the vault). Money in and out with a reason, a
    // running balance, and a day close that counts the notes by denomination. Insert-only, like the stock movements: a
    // mistake is put right by a new row that reverses the old one, never by editing. Cash taken on sales, order advances
    // and customer advances is not copied here: the Main book reads it from where it was recorded.
    public function up(): void
    {
        Schema::create('cash_entries', function (Blueprint $table) {
            $table->id();
            $table->enum('book', ['main', 'petty']);
            $table->enum('kind', ['opening', 'receipt', 'payment']);
            $table->decimal('amount', 12, 2);                       // always positive; the kind says which way it goes
            $table->date('occurred_on');
            $table->string('reason', 255);
            $table->string('note', 255)->nullable();
            $table->string('transfer_id', 36)->nullable()->index(); // the two rows of a move between the books share it
            $table->foreignId('reverses_id')->nullable()->constrained('cash_entries'); // set on the row that puts another right
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('created_at')->useCurrent();
            $table->index(['book', 'occurred_on']);
        });

        Schema::create('cash_day_closes', function (Blueprint $table) {
            $table->id();
            $table->enum('book', ['main', 'petty']);
            $table->date('closed_on');
            $table->decimal('expected', 12, 2);                     // what the book says is there
            $table->decimal('counted', 12, 2);                      // what was counted
            $table->decimal('difference', 12, 2);                   // counted less expected
            $table->json('denominations');                          // {"500": 12, "100": 3, ...}
            $table->string('note', 255)->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('created_at')->useCurrent();
            $table->index(['book', 'closed_on']);
        });

        $permission = Permission::firstOrCreate(['name' => 'cashbook.manage', 'guard_name' => 'web']);
        foreach (['owner', 'manager'] as $name) {
            Role::where('name', $name)->first()?->givePermissionTo($permission);
        }
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_day_closes');
        Schema::dropIfExists('cash_entries');
        Permission::where('name', 'cashbook.manage')->delete();
    }
};

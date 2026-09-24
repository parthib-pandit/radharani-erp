<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Confirmed requirements this covers:
// - #6 weight loss/wastage on karigar and hallmarking returns is expected
//   and normal; staff manually enter the loss figure at return time — this
//   was previously smuggled into the free-text `note` field, not a real
//   column. `weight_at_return` is the actual weighed-in figure;
//   `weight_loss` is the manually-entered loss (not auto-computed, per the
//   client — the two are kept separate rather than derived from each other).
// - #8 hallmarking returns require a "tagged by" field recording, by name,
//   whoever performed the tagging — this can be an external hallmarking
//   centre person, not necessarily a system user, so it's free text rather
//   than a `users` foreign key (approved_by was being misused for this).
return new class extends Migration {
    public function up(): void
    {
        Schema::table('movements', function (Blueprint $table) {
            $table->decimal('weight_at_return', 8, 3)->nullable()->after('weight_at_dispatch');
            $table->decimal('weight_loss', 8, 3)->nullable()->after('weight_at_return');
            $table->string('tagged_by', 100)->nullable()->after('weight_loss');
        });
    }

    public function down(): void
    {
        Schema::table('movements', function (Blueprint $table) {
            $table->dropColumn(['weight_at_return', 'weight_loss', 'tagged_by']);
        });
    }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// Matches the items.metal widening — rate history needs to track titanium
// and platinum rates too, not just gold/silver (#18 taxonomy discussion).
return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE rate_logs MODIFY metal ENUM('gold','silver','titanium','platinum') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE rate_logs MODIFY metal ENUM('gold','silver') NOT NULL");
    }
};

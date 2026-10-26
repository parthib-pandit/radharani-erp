<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // 25 Oct feedback. A sale can price a piece at a different weight than the one on its record (the piece itself never
    // changes), and staff can bargain the making rate down per piece. The sale keeps what was used next to what was listed
    // so the verifier can see the difference. The bargain discount after GST and the round-off (the Tally "Discount Sales"
    // line) is its own figure on the sale. All new columns are written once, when the sale is created.
    public function up(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->decimal('weight_listed', 10, 3)->nullable()->after('price_at_sale'); // the weight the price would have used
            $table->decimal('weight_at_sale', 10, 3)->nullable()->after('weight_listed'); // the weight it was priced at
            $table->string('weight_note', 255)->nullable()->after('weight_at_sale');
            $table->string('making_kind', 20)->nullable()->after('weight_note'); // percentage | per_gram | per_piece
            $table->decimal('making_default_rate', 10, 2)->nullable()->after('making_kind');
            $table->decimal('making_rate', 10, 2)->nullable()->after('making_default_rate'); // what the sale used
            $table->decimal('making_amount', 12, 2)->nullable()->after('making_rate'); // the making charge in rupees
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('final_discount', 10, 2)->default(0)->after('round_off');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('final_discount');
        });
        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropColumn(['weight_listed', 'weight_at_sale', 'weight_note', 'making_kind', 'making_default_rate', 'making_rate', 'making_amount']);
        });
    }
};

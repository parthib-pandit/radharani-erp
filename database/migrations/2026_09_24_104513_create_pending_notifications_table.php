<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// #16 WhatsApp auto-send is not being integrated this phase. Every
// customer-facing notification (sale confirmation, order ready, loyalty
// award, instalment reminder, exchange valuation ready) follows one shared
// pattern: the system generates a copyable message, staff copies and sends
// it manually, then marks it "sent" here. One generic table across all
// message types rather than a separate feature per type, per the design
// decision on this project. `related_type`/`related_id` optionally point
// back at the record that triggered the message (a sale, an order, etc.)
// for context, without a rigid foreign key per type.
return new class extends Migration {
    public function up(): void
    {
        Schema::create('pending_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->enum('type', ['sale_confirmation', 'order_ready', 'loyalty_award', 'installment_reminder', 'exchange_valuation_ready', 'other'])->default('other');
            $table->string('recipient_name', 100)->nullable();
            $table->string('recipient_phone', 20)->nullable();
            $table->text('message');
            $table->enum('status', ['pending', 'sent'])->default('pending')->index();
            $table->foreignId('sent_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('sent_at')->nullable();
            $table->string('related_type', 50)->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pending_notifications');
    }
};

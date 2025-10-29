<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('deal_id')->constrained('deals')->onDelete('cascade');

            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount_percent', 5, 2);
            $table->decimal('discount_amount', 10, 2);

            $table->enum('status', ['draft', 'pending_approval', 'approved', 'rejected', 'sent'])->default('draft');

            $table->foreignId('approved_by')->constrained('users')->onDelete('cascade');

            $table->text('approval_reason')->nullable();

            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};

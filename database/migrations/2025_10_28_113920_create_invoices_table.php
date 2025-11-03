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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('deal_id')->constrained('deals')->onDelete('cascade');

            $table->morphs('client');

            $table->json('items');

            $table->unsignedInteger('subtotal');

            $table->decimal('total_amount', 10, 2); 
            $table->decimal('tax_amount', 10, 2)->nullable();

            $table->enum('status', ['pending', 'paid', 'overdue', 'cancelled'])->default('pending');
 
            $table->timestamp('issued_at');
            $table->timestamp('due_date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

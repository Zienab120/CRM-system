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
        Schema::create('campaign_sends', function (Blueprint $table) {
            $table->id();

            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->foreignId('lead_id')->constrained('leads')->onDelete('cascade');

            $table->enum('status', ['queued', 'sent', 'delivered', 'bounced', 'failed', 'opened', 'clicked', 'unsubscribed'])->default('queued');

            // $table->string('provider_message_id')->nullable();

            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_sends');
    }
};

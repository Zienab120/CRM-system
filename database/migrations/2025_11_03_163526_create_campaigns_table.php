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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();

            $table->string('name')->index();

            $table->enum('type', ['email', 'sms', 'ads', 'social']);
            $table->enum('status', ['draft', 'scheduled', 'sending', 'sent', 'paused'])->default('draft');

            $table->json('audience_filter')->nullable();

            $table->foreignId('template_id')->nullable()->constrained('email_templates');
            $table->foreignId('created_by')->constrained('users');
            
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};

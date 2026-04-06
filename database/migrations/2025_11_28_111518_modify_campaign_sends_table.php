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
        Schema::table('campaign_sends', function (Blueprint $table) {
            $table->foreignId('user_email_id')->constrained('user_emails')->onDelete('cascade');

            $table->dropForeign(['lead_id']);
            $table->foreignId('lead_id')->nullable()->change();

            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaign_sends', function (Blueprint $table) {
            $table->dropForeign(['user_email_id']);
            $table->dropColumn('user_email_id');

            $table->dropForeign(['lead_id']);
            $table->foreignId('lead_id')->nullable(false)->change();

            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
        });
    }
};

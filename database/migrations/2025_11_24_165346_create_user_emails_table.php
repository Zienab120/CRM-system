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
        Schema::create('user_emails', function (Blueprint $table) {
            $table->id();

            $table->string('email', 100)->unique();
            $table->boolean('is_mail_sent')->default(false);

            $table->unsignedSmallInteger('score')->default(0);

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_emails');
    }
};

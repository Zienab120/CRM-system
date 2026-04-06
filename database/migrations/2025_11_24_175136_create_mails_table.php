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
        // Schema::create('mails', function (Blueprint $table) {
        //     $table->id();

        //     $table->string('email', 100);

        //     $table->enum('status', ['sent', 'opened', 'clicked', 'filled', 'unsubscribed'])
        //         ->default('sent');

        //     $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');

        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mails');
    }
};

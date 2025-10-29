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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('status')->nullable();

            $table->enum('type', ['call', 'meeting', 'task']);

            $table->text('description')->nullable();

            $table->string('duration')->nullable();

            $table->timestamp('start_at')->nullable();
            $table->timestamp('due_at')->nullable();

            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');

            $table->morphs('related');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};

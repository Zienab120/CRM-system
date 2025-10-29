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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('stage');

            $table->foreignId('contact_id')->constrained('contacts')->onDelete('cascade');
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('onwer_id')->constrained('users')->onDelete('cascade');

            $table->decimal('probability');
            $table->decimal('amount');

            $table->string('currency', 5);

            $table->unsignedTinyInteger('pipeline_id');

            $table->json('custom_fields')->nullable();

            $table->timestamp('close_expected_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};

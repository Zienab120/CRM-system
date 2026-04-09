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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contact_id')->constrained('contacts')->onDelete('cascade');
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('assigned_to')->constrained('users')->onDelete('cascade'); // sales manager
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade'); // who assigned the lead

            $table->enum('status', ['new', 'working', 'qualified', 'unqualified'])->default('new')->index();

            $table->string('source');

            $table->unsignedSmallInteger('score')->default(0);

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};

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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // Unique URL identifier
            $table->string('name')->nullable(); // Team name, set by team
            $table->boolean('is_ready')->default(false); // Ready status
            $table->text('cipher_text')->nullable(); // The encrypted challenge text
            $table->text('solution')->nullable(); // Team's submitted solution
            $table->integer('shift')->nullable(); // Caesar shift value for this team
            $table->boolean('is_correct')->default(false); // Whether solution is correct
            $table->timestamp('completed_at')->nullable(); // When they completed the challenge
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};

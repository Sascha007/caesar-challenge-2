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
        Schema::create('game_state', function (Blueprint $table) {
            $table->id();
            $table->string('state')->default('preparing'); // preparing, running, stopped
            $table->text('challenge_text')->nullable(); // The original text to encrypt
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_state');
    }
};

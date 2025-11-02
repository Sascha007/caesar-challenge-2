<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\GameState::firstOrCreate(
            ['id' => 1],
            [
                'state' => 'preparing',
                'challenge_text' => 'Ein Geheimnis teilt man nur mit dem, der den Schlüssel hat.',
            ]
        );
    }
}

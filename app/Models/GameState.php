<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameState extends Model
{
    use HasFactory;

    protected $table = 'game_state';

    protected $fillable = [
        'state',
        'challenge_text',
    ];

    public static function current()
    {
        return static::firstOrCreate(
            ['id' => 1],
            ['state' => 'preparing', 'challenge_text' => '']
        );
    }
}

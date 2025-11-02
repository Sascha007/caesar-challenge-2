<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'is_ready',
        'cipher_text',
        'solution',
        'shift',
        'is_correct',
        'completed_at',
    ];

    protected $casts = [
        'is_ready' => 'boolean',
        'is_correct' => 'boolean',
        'completed_at' => 'datetime',
    ];
}

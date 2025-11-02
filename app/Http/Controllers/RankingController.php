<?php

namespace App\Http\Controllers;

use App\Models\GameState;
use App\Models\Team;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function index()
    {
        $gameState = GameState::current();
        $teams = Team::whereNotNull('name')
            ->where('is_ready', true)
            ->orderBy('is_correct', 'desc')
            ->orderByRaw('CASE WHEN completed_at IS NULL THEN 1 ELSE 0 END')
            ->orderBy('completed_at', 'asc')
            ->get();

        return view('ranking.index', compact('gameState', 'teams'));
    }

    public function updates()
    {
        $gameState = GameState::current();
        $teams = Team::whereNotNull('name')
            ->where('is_ready', true)
            ->orderBy('is_correct', 'desc')
            ->orderByRaw('CASE WHEN completed_at IS NULL THEN 1 ELSE 0 END')
            ->orderBy('completed_at', 'asc')
            ->get()
            ->map(function ($team) {
                return [
                    'id' => $team->id,
                    'name' => $team->name,
                    'is_correct' => $team->is_correct,
                    'completed_at' => $team->completed_at?->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'game_state' => [
                'state' => $gameState->state,
            ],
            'teams' => $teams,
        ]);
    }
}

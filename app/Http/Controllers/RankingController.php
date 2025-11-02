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
            ->orderByRaw('is_correct DESC, completed_at ASC NULLS LAST')
            ->get();

        return view('ranking.index', compact('gameState', 'teams'));
    }

    public function updates()
    {
        $gameState = GameState::current();
        $teams = Team::whereNotNull('name')
            ->where('is_ready', true)
            ->orderByRaw('is_correct DESC, completed_at ASC NULLS LAST')
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

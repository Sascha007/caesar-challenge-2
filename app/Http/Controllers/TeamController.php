<?php

namespace App\Http\Controllers;

use App\Models\GameState;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function show($slug)
    {
        $team = Team::where('slug', $slug)->firstOrFail();
        $gameState = GameState::current();

        return view('team.show', compact('team', 'gameState'));
    }

    public function setName(Request $request, $slug)
    {
        $team = Team::where('slug', $slug)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $team->name = $request->input('name');
        $team->save();

        return redirect()->route('team.show', $slug)
            ->with('success', 'Team name updated successfully!');
    }

    public function toggleReady($slug)
    {
        $team = Team::where('slug', $slug)->firstOrFail();
        $gameState = GameState::current();

        if ($gameState->state !== 'preparing') {
            return redirect()->route('team.show', $slug)
                ->with('error', 'Cannot change ready status while game is in progress.');
        }

        $team->is_ready = !$team->is_ready;
        $team->save();

        return redirect()->route('team.show', $slug)
            ->with('success', 'Ready status updated!');
    }

    public function submitSolution(Request $request, $slug)
    {
        $team = Team::where('slug', $slug)->firstOrFail();
        $gameState = GameState::current();

        if ($gameState->state !== 'running') {
            return redirect()->route('team.show', $slug)
                ->with('error', 'Game is not currently running.');
        }

        if ($team->is_correct) {
            return redirect()->route('team.show', $slug)
                ->with('info', 'You have already solved the challenge!');
        }

        $request->validate([
            'solution' => 'required|string',
        ]);

        $solution = trim($request->input('solution'));
        $team->solution = $solution;

        // Check if solution is correct (case-insensitive, ignore extra whitespace)
        $correctAnswer = trim($gameState->challenge_text);
        $isCorrect = strcasecmp($solution, $correctAnswer) === 0;

        $team->is_correct = $isCorrect;
        if ($isCorrect && !$team->completed_at) {
            $team->completed_at = now();
        }

        $team->save();

        if ($isCorrect) {
            return redirect()->route('team.show', $slug)
                ->with('success', 'Correct! You solved the challenge!');
        } else {
            return redirect()->route('team.show', $slug)
                ->with('error', 'Incorrect solution. Try again!');
        }
    }

    public function updates($slug)
    {
        $team = Team::where('slug', $slug)->firstOrFail();
        $gameState = GameState::current();

        return response()->json([
            'team' => [
                'name' => $team->name,
                'is_ready' => $team->is_ready,
                'cipher_text' => $team->cipher_text,
                'solution' => $team->solution,
                'is_correct' => $team->is_correct,
            ],
            'game_state' => [
                'state' => $gameState->state,
            ],
        ]);
    }
}

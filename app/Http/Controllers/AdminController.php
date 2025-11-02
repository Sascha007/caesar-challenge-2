<?php

namespace App\Http\Controllers;

use App\Models\GameState;
use App\Models\Team;
use App\Services\CaesarService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function __construct(private CaesarService $caesarService)
    {
    }

    public function index()
    {
        $gameState = GameState::current();
        $teams = Team::orderBy('created_at')->get();

        return view('admin.index', compact('gameState', 'teams'));
    }

    public function createTeam(Request $request)
    {
        $request->validate([
            'count' => 'required|integer|min:1|max:50',
        ]);

        $count = $request->input('count');
        $created = [];

        for ($i = 0; $i < $count; $i++) {
            $slug = Str::random(8);
            while (Team::where('slug', $slug)->exists()) {
                $slug = Str::random(8);
            }

            $created[] = Team::create([
                'slug' => $slug,
            ]);
        }

        return redirect()->route('admin.index')
            ->with('success', "Created {$count} team(s) successfully.");
    }

    public function deleteTeam(Team $team)
    {
        $team->delete();
        return redirect()->route('admin.index')
            ->with('success', 'Team deleted successfully.');
    }

    public function setChallengeText(Request $request)
    {
        $request->validate([
            'challenge_text' => 'required|string|max:1000',
        ]);

        $gameState = GameState::current();
        $gameState->challenge_text = $request->input('challenge_text');
        $gameState->save();

        return redirect()->route('admin.index')
            ->with('success', 'Challenge text updated successfully.');
    }

    public function startGame()
    {
        $gameState = GameState::current();

        if (empty($gameState->challenge_text)) {
            return redirect()->route('admin.index')
                ->with('error', 'Please set challenge text before starting the game.');
        }

        $gameState->state = 'running';
        $gameState->save();

        // Assign cipher text to each ready team
        $teams = Team::where('is_ready', true)->get();
        foreach ($teams as $team) {
            $shift = rand(1, 25);
            $team->shift = $shift;
            $team->cipher_text = $this->caesarService->encrypt($gameState->challenge_text, $shift);
            $team->save();
        }

        return redirect()->route('admin.index')
            ->with('success', 'Game started successfully!');
    }

    public function stopGame()
    {
        $gameState = GameState::current();
        $gameState->state = 'stopped';
        $gameState->save();

        return redirect()->route('admin.index')
            ->with('success', 'Game stopped successfully.');
    }

    public function resetGame()
    {
        $gameState = GameState::current();
        $gameState->state = 'preparing';
        $gameState->save();

        // Reset all teams
        Team::query()->update([
            'is_ready' => false,
            'cipher_text' => null,
            'solution' => null,
            'shift' => null,
            'is_correct' => false,
            'completed_at' => null,
        ]);

        return redirect()->route('admin.index')
            ->with('success', 'Game reset successfully.');
    }
}

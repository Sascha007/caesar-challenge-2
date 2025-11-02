<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('admin.index');
});

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::post('/teams/create', [AdminController::class, 'createTeam'])->name('teams.create');
    Route::delete('/teams/{team}', [AdminController::class, 'deleteTeam'])->name('teams.delete');
    Route::post('/challenge-text', [AdminController::class, 'setChallengeText'])->name('challenge.set');
    Route::post('/game/start', [AdminController::class, 'startGame'])->name('game.start');
    Route::post('/game/stop', [AdminController::class, 'stopGame'])->name('game.stop');
    Route::post('/game/reset', [AdminController::class, 'resetGame'])->name('game.reset');
});

// Ranking/Leaderboard routes
Route::get('/ranking', [RankingController::class, 'index'])->name('ranking.index');
Route::get('/ranking/updates', [RankingController::class, 'updates'])->name('ranking.updates');

// Team routes
Route::prefix('team')->name('team.')->group(function () {
    Route::get('/{slug}', [TeamController::class, 'show'])->name('show');
    Route::post('/{slug}/name', [TeamController::class, 'setName'])->name('name.set');
    Route::post('/{slug}/ready', [TeamController::class, 'toggleReady'])->name('ready.toggle');
    Route::post('/{slug}/solution', [TeamController::class, 'submitSolution'])->name('solution.submit');
    Route::get('/{slug}/updates', [TeamController::class, 'updates'])->name('updates');
});

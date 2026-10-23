@extends('layouts.app')

@section('title', 'Live Ranking - Caesar Challenge')

@section('content')
<div class="max-w-6xl mx-auto" x-data="rankingPage()">
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h1 class="text-4xl font-bold mb-2">🏆 Live Ranking</h1>
        <p class="text-gray-600">Real-time leaderboard</p>
    </div>

    <!-- Game Status -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center space-x-4">
            <span class="text-lg font-semibold">Game Status:</span>
            <span class="px-4 py-2 rounded-full font-bold" 
                  :class="{
                      'bg-yellow-200 text-yellow-800': gameState === 'preparing',
                      'bg-green-200 text-green-800': gameState === 'running',
                      'bg-red-200 text-red-800': gameState === 'stopped'
                  }"
                  x-text="gameState.toUpperCase()">
            </span>
        </div>
    </div>

    <!-- Rankings Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Team Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed At</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <template x-for="(team, index) in teams" :key="team.id">
                    <tr :class="team.is_correct ? 'bg-green-50' : ''">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-2xl font-bold" x-text="index + 1"></span>
                                <span x-show="index === 0 && team.is_correct" class="ml-2 text-2xl">🥇</span>
                                <span x-show="index === 1 && team.is_correct" class="ml-2 text-2xl">🥈</span>
                                <span x-show="index === 2 && team.is_correct" class="ml-2 text-2xl">🥉</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-lg font-semibold" x-text="team.name"></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span x-show="team.is_correct" class="px-3 py-1 bg-green-500 text-white rounded-full text-sm font-semibold">
                                ✓ Solved
                            </span>
                            <span x-show="!team.is_correct" class="px-3 py-1 bg-gray-300 text-gray-700 rounded-full text-sm">
                                In Progress
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span x-text="team.completed_at || '-'"></span>
                        </td>
                    </tr>
                </template>
                <tr x-show="teams.length === 0">
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                        <div class="text-lg">No teams have registered yet.</div>
                        <div class="text-sm mt-2">Teams will appear here once they set their name and mark themselves as ready.</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Stats Summary -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-3xl font-bold text-blue-600" x-text="teams.length"></div>
            <div class="text-gray-600">Total Teams</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-3xl font-bold text-green-600" x-text="solvedCount"></div>
            <div class="text-gray-600">Solved</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-3xl font-bold text-yellow-600" x-text="teams.length - solvedCount"></div>
            <div class="text-gray-600">In Progress</div>
        </div>
    </div>
</div>

<script>
    function rankingPage() {
        return {
            gameState: '{{ $gameState->state }}',
            teams: @json($teams),
            
            get solvedCount() {
                return this.teams.filter(team => team.is_correct).length;
            },
            
            init() {
                // Poll for updates every 2 seconds
                setInterval(() => {
                    this.fetchUpdates();
                }, 2000);
            },
            
            async fetchUpdates() {
                try {
                    const response = await fetch('{{ route('ranking.updates') }}');
                    const data = await response.json();
                    
                    this.gameState = data.game_state.state;
                    this.teams = data.teams;
                } catch (error) {
                    console.error('Failed to fetch updates:', error);
                }
            }
        }
    }
</script>
@endsection

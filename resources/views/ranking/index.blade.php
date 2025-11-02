@extends('layouts.app')

@section('title', 'Live Ranking - Caesar Challenge')

@section('content')
<div class="max-w-6xl mx-auto" x-data="rankingPage()">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-yellow-400 via-orange-500 to-red-500 rounded-2xl shadow-2xl p-8 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-5xl font-bold mb-2 flex items-center space-x-3">
                    <span>🏆</span>
                    <span>Live Ranking</span>
                </h1>
                <p class="text-xl text-orange-100">Real-time competition leaderboard</p>
            </div>
            <div class="hidden md:block">
                <div class="bg-white/20 backdrop-blur-sm p-6 rounded-xl text-center">
                    <div class="text-4xl font-bold mb-1" x-text="teams.length"></div>
                    <div class="text-sm font-semibold text-orange-100">Teams Competing</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Game Status -->
    <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 border-2 border-gray-100">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="bg-gradient-to-br from-blue-500 to-purple-600 p-3 rounded-xl text-white">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-xl font-bold text-gray-700">Game Status:</span>
            </div>
            <span class="px-6 py-3 rounded-full font-bold text-white shadow-lg" 
                  :class="{
                      'bg-gradient-to-r from-yellow-400 to-orange-500': gameState === 'preparing',
                      'bg-gradient-to-r from-green-400 to-emerald-500': gameState === 'running',
                      'bg-gradient-to-r from-red-400 to-pink-500': gameState === 'stopped'
                  }"
                  x-text="gameState.toUpperCase()">
            </span>
        </div>
    </div>

    <!-- Rankings Table -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border-2 border-gray-100 mb-8">
        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 px-8 py-6">
            <h2 class="text-2xl font-bold text-white">Competition Leaderboard</h2>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">Rank</th>
                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">Team Name</th>
                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">Completed At</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <template x-for="(team, index) in teams" :key="team.id">
                    <tr :class="team.is_correct ? 'bg-green-50 hover:bg-green-100' : 'hover:bg-gray-50'" class="transition-colors duration-150">
                        <td class="px-6 py-5 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <span class="text-3xl font-bold" 
                                      :class="{
                                          'text-yellow-500': index === 0 && team.is_correct,
                                          'text-gray-400': index === 1 && team.is_correct,
                                          'text-orange-600': index === 2 && team.is_correct,
                                          'text-gray-600': index > 2 || !team.is_correct
                                      }"
                                      x-text="index + 1"></span>
                                <span x-show="index === 0 && team.is_correct" class="text-4xl">🥇</span>
                                <span x-show="index === 1 && team.is_correct" class="text-4xl">🥈</span>
                                <span x-show="index === 2 && team.is_correct" class="text-4xl">🥉</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <div class="text-xl font-bold text-gray-900" x-text="team.name"></div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <span x-show="team.is_correct" class="px-4 py-2 bg-gradient-to-r from-green-400 to-emerald-500 text-white rounded-full text-sm font-bold shadow-md inline-flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Solved</span>
                            </span>
                            <span x-show="!team.is_correct" class="px-4 py-2 bg-gradient-to-r from-gray-300 to-gray-400 text-gray-700 rounded-full text-sm font-semibold inline-flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                </svg>
                                <span>In Progress</span>
                            </span>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-600 font-mono">
                            <span x-text="team.completed_at || '-'"></span>
                        </td>
                    </tr>
                </template>
                <tr x-show="teams.length === 0">
                    <td colspan="4" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center space-y-4">
                            <svg class="w-24 h-24 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                            <div>
                                <p class="text-2xl font-bold text-gray-700 mb-2">No teams have registered yet</p>
                                <p class="text-gray-500">Teams will appear here once they set their name and mark themselves as ready.</p>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-8 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between mb-2">
                <svg class="w-12 h-12 opacity-80" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
                <div class="text-5xl font-bold" x-text="teams.length"></div>
            </div>
            <div class="text-lg font-semibold opacity-90">Total Teams</div>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-xl p-8 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between mb-2">
                <svg class="w-12 h-12 opacity-80" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div class="text-5xl font-bold" x-text="solvedCount"></div>
            </div>
            <div class="text-lg font-semibold opacity-90">Solved</div>
        </div>
        <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl shadow-xl p-8 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between mb-2">
                <svg class="w-12 h-12 opacity-80" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
                <div class="text-5xl font-bold" x-text="teams.length - solvedCount"></div>
            </div>
            <div class="text-lg font-semibold opacity-90">In Progress</div>
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

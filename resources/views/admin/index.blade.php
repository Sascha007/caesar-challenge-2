@extends('layouts.app')

@section('title', 'Admin Panel - Caesar Challenge')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{ 
    gameState: '{{ $gameState->state }}',
    challengeText: '{{ $gameState->challenge_text }}' 
}">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent mb-2">
            Admin Control Panel
        </h1>
        <p class="text-gray-600">Manage teams, configure challenges, and control the game flow</p>
    </div>

    <!-- Game Status Card -->
    <div class="bg-white rounded-2xl shadow-xl p-8 mb-6 border border-gray-100 hover:shadow-2xl transition-shadow duration-300">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Game Status</h2>
                <p class="text-gray-500">Current competition state</p>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-lg font-medium text-gray-700">Status:</span>
                <span class="px-6 py-3 rounded-full font-bold text-white shadow-lg transform hover:scale-105 transition-transform duration-200" 
                      :class="{
                          'bg-gradient-to-r from-yellow-400 to-orange-500': gameState === 'preparing',
                          'bg-gradient-to-r from-green-400 to-emerald-500': gameState === 'running',
                          'bg-gradient-to-r from-red-400 to-pink-500': gameState === 'stopped'
                      }"
                      x-text="gameState.toUpperCase()">
                </span>
            </div>
        </div>
    </div>

    <!-- Challenge Text Configuration -->
    <div class="bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-xl p-8 mb-6 border border-blue-100 hover:shadow-2xl transition-shadow duration-300">
        <div class="flex items-center space-x-3 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-purple-600 p-3 rounded-xl text-white">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Challenge Text</h2>
                <p class="text-gray-500">Set the text that teams will decrypt</p>
            </div>
        </div>
        <form action="{{ route('admin.challenge.set') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label for="challenge_text" class="block text-sm font-semibold text-gray-700 mb-3">
                    Original Text (will be encrypted for each team)
                </label>
                <textarea 
                    name="challenge_text" 
                    id="challenge_text" 
                    rows="4" 
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-300 focus:border-blue-500 transition-all duration-200 font-mono"
                    placeholder="Enter the text that teams will need to decrypt..."
                    required
                    x-model="challengeText"
                >{{ $gameState->challenge_text }}</textarea>
            </div>
            <button 
                type="submit" 
                class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-8 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                :disabled="gameState !== 'preparing'"
                :class="{ 'opacity-50 cursor-not-allowed': gameState !== 'preparing' }">
                <span class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>Save Challenge Text</span>
                </span>
            </button>
        </form>
    </div>

    <!-- Game Controls -->
    <div class="bg-white rounded-2xl shadow-xl p-8 mb-6 border border-gray-100 hover:shadow-2xl transition-shadow duration-300">
        <div class="flex items-center space-x-3 mb-6">
            <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-3 rounded-xl text-white">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Game Controls</h2>
                <p class="text-gray-500">Start, stop, or reset the competition</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <form action="{{ route('admin.game.start') }}" method="POST">
                @csrf
                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200"
                    :disabled="gameState !== 'preparing' || !challengeText"
                    :class="{ 'opacity-50 cursor-not-allowed': gameState !== 'preparing' || !challengeText }">
                    <span class="flex items-center justify-center space-x-2">
                        <span class="text-2xl">🚀</span>
                        <span>Start Game</span>
                    </span>
                </button>
            </form>

            <form action="{{ route('admin.game.stop') }}" method="POST">
                @csrf
                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white px-6 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200"
                    :disabled="gameState !== 'running'"
                    :class="{ 'opacity-50 cursor-not-allowed': gameState !== 'running' }">
                    <span class="flex items-center justify-center space-x-2">
                        <span class="text-2xl">⏸️</span>
                        <span>Stop Game</span>
                    </span>
                </button>
            </form>

            <form action="{{ route('admin.game.reset') }}" method="POST">
                @csrf
                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700 text-white px-6 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200"
                    onclick="return confirm('Are you sure you want to reset the game? This will clear all team progress.')">
                    <span class="flex items-center justify-center space-x-2">
                        <span class="text-2xl">🔄</span>
                        <span>Reset Game</span>
                    </span>
                </button>
            </form>
        </div>
    </div>

    <!-- Teams Management -->
    <div class="bg-white rounded-2xl shadow-xl p-8 mb-6 border border-gray-100 hover:shadow-2xl transition-shadow duration-300">
        <div class="flex items-center space-x-3 mb-6">
            <div class="bg-gradient-to-br from-indigo-500 to-blue-600 p-3 rounded-xl text-white">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Teams Management</h2>
                <p class="text-gray-500">Create and manage competition teams</p>
            </div>
        </div>
        
        <!-- Create Teams Form -->
        <form action="{{ route('admin.teams.create') }}" method="POST" class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border-2 border-blue-100">
            @csrf
            <div class="flex flex-col sm:flex-row items-end space-y-4 sm:space-y-0 sm:space-x-4">
                <div class="flex-1">
                    <label for="count" class="block text-sm font-semibold text-gray-700 mb-2">
                        Number of Teams to Create
                    </label>
                    <input 
                        type="number" 
                        name="count" 
                        id="count" 
                        min="1" 
                        max="50" 
                        value="5"
                        class="w-full px-4 py-3 border-2 border-blue-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-300 focus:border-blue-500 transition-all duration-200"
                        required>
                </div>
                <button 
                    type="submit" 
                    class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <span class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>Create Teams</span>
                    </span>
                </button>
            </div>
        </form>

        <!-- Teams Table -->
        <div class="overflow-hidden rounded-xl border-2 border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Ready</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($teams as $team)
                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $team->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono bg-gray-50 text-gray-700 rounded">{{ $team->slug }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $team->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($team->is_ready)
                                    <span class="px-3 py-1 bg-gradient-to-r from-green-400 to-emerald-500 text-white rounded-full text-xs font-bold shadow-sm">✓ Ready</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-xs font-semibold">Not Ready</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($team->is_correct)
                                    <span class="px-3 py-1 bg-gradient-to-r from-green-400 to-emerald-500 text-white rounded-full text-xs font-bold shadow-sm">✓ Solved</span>
                                @elseif($team->solution)
                                    <span class="px-3 py-1 bg-gradient-to-r from-yellow-400 to-orange-500 text-white rounded-full text-xs font-bold shadow-sm">Attempted</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-xs font-semibold">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-3">
                                <a href="{{ route('team.show', $team->slug) }}" 
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
                                    View
                                </a>
                                <form action="{{ route('admin.teams.delete', $team) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit" 
                                        class="text-red-600 hover:text-red-800 font-semibold hover:underline"
                                        onclick="return confirm('Are you sure you want to delete this team?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center space-y-3">
                                    <svg class="w-16 h-16 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-500">No teams created yet</p>
                                    <p class="text-sm text-gray-400">Create some teams to get started with the competition!</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl shadow-xl p-8 text-white hover:shadow-2xl transition-shadow duration-300">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Quick Access</h2>
                <p class="text-purple-100">View the live competition leaderboard</p>
            </div>
            <a href="{{ route('ranking.index') }}" 
               target="_blank"
               class="bg-white text-purple-600 px-8 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 flex items-center space-x-3">
                <span class="text-2xl">🏆</span>
                <span>Live Ranking</span>
            </a>
        </div>
    </div>
</div>
@endsection

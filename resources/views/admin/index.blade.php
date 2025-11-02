@extends('layouts.app')

@section('title', 'Admin Panel - Caesar Challenge')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{ 
    gameState: '{{ $gameState->state }}',
    challengeText: '{{ $gameState->challenge_text }}' 
}">
    <h1 class="text-3xl font-bold mb-6">Admin Panel</h1>

    <!-- Game State Status -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Game Status</h2>
        <div class="flex items-center space-x-4">
            <span class="text-lg">Current State:</span>
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

    <!-- Challenge Text -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Challenge Text</h2>
        <form action="{{ route('admin.challenge.set') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="challenge_text" class="block text-sm font-medium text-gray-700 mb-2">
                    Text to Encrypt (teams will decode this)
                </label>
                <textarea 
                    name="challenge_text" 
                    id="challenge_text" 
                    rows="4" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter the text that teams will need to decrypt..."
                    required
                    x-model="challengeText"
                >{{ $gameState->challenge_text }}</textarea>
            </div>
            <button 
                type="submit" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md"
                :disabled="gameState !== 'preparing'"
                :class="{ 'opacity-50 cursor-not-allowed': gameState !== 'preparing' }">
                Set Challenge Text
            </button>
        </form>
    </div>

    <!-- Game Controls -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Game Controls</h2>
        <div class="flex space-x-4">
            <form action="{{ route('admin.game.start') }}" method="POST">
                @csrf
                <button 
                    type="submit" 
                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-md font-semibold"
                    :disabled="gameState !== 'preparing' || !challengeText"
                    :class="{ 'opacity-50 cursor-not-allowed': gameState !== 'preparing' || !challengeText }">
                    🚀 Start Game
                </button>
            </form>

            <form action="{{ route('admin.game.stop') }}" method="POST">
                @csrf
                <button 
                    type="submit" 
                    class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-md font-semibold"
                    :disabled="gameState !== 'running'"
                    :class="{ 'opacity-50 cursor-not-allowed': gameState !== 'running' }">
                    ⏸️ Stop Game
                </button>
            </form>

            <form action="{{ route('admin.game.reset') }}" method="POST">
                @csrf
                <button 
                    type="submit" 
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-md font-semibold"
                    onclick="return confirm('Are you sure you want to reset the game? This will clear all team progress.')">
                    🔄 Reset Game
                </button>
            </form>
        </div>
    </div>

    <!-- Teams Management -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Teams Management</h2>
        
        <!-- Create Teams Form -->
        <form action="{{ route('admin.teams.create') }}" method="POST" class="mb-6">
            @csrf
            <div class="flex items-end space-x-4">
                <div>
                    <label for="count" class="block text-sm font-medium text-gray-700 mb-2">
                        Number of Teams to Create
                    </label>
                    <input 
                        type="number" 
                        name="count" 
                        id="count" 
                        min="1" 
                        max="50" 
                        value="5"
                        class="w-32 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>
                <button 
                    type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                    Create Teams
                </button>
            </div>
        </form>

        <!-- Teams List -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ready</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($teams as $team)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $team->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono">{{ $team->slug }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $team->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($team->is_ready)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Ready</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Not Ready</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($team->is_correct)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">✓ Solved</span>
                                @elseif($team->solution)
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Attempted</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                <a href="{{ route('team.show', $team->slug) }}" 
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-800">
                                    View
                                </a>
                                <form action="{{ route('admin.teams.delete', $team) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit" 
                                        class="text-red-600 hover:text-red-800"
                                        onclick="return confirm('Are you sure you want to delete this team?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No teams created yet. Create some teams to get started!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Quick Links</h2>
        <div class="space-y-2">
            <a href="{{ route('ranking.index') }}" 
               target="_blank"
               class="block text-blue-600 hover:text-blue-800 hover:underline">
                🏆 View Live Ranking/Leaderboard
            </a>
        </div>
    </div>
</div>

<script>
    // Auto-refresh page every 5 seconds to see updates
    setTimeout(() => {
        location.reload();
    }, 5000);
</script>
@endsection

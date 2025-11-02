@extends('layouts.app')

@section('title', 'Team ' . ($team->name ?? $team->slug) . ' - Caesar Challenge')

@section('content')
<div class="max-w-4xl mx-auto" x-data="teamPage()">
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h1 class="text-3xl font-bold mb-2">Team Portal</h1>
        <p class="text-gray-600">Team ID: <span class="font-mono">{{ $team->slug }}</span></p>
    </div>

    <!-- Team Name Setup -->
    @if(!$team->name)
        <div class="bg-blue-50 border border-blue-200 rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">👥 Set Your Team Name</h2>
            <form action="{{ route('team.name.set', $team->slug) }}" method="POST">
                @csrf
                <div class="flex space-x-4">
                    <input 
                        type="text" 
                        name="name" 
                        placeholder="Enter your team name..." 
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                        maxlength="100">
                    <button 
                        type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md font-semibold">
                        Set Name
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold">Team Name: <span class="text-blue-600" x-text="teamName">{{ $team->name }}</span></h2>
        </div>
    @endif

    <!-- Ready Status -->
    @if($team->name)
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Ready Status</h2>
            <div class="flex items-center space-x-4">
                <form action="{{ route('team.ready.toggle', $team->slug) }}" method="POST">
                    @csrf
                    <button 
                        type="submit"
                        class="px-6 py-3 rounded-md font-semibold text-lg"
                        :class="isReady ? 'bg-green-500 hover:bg-green-600 text-white' : 'bg-gray-300 hover:bg-gray-400 text-gray-700'"
                        :disabled="gameState !== 'preparing'"
                        x-bind:disabled="gameState !== 'preparing'">
                        <span x-show="isReady">✓ Ready!</span>
                        <span x-show="!isReady">Mark as Ready</span>
                    </button>
                </form>
                <p class="text-sm text-gray-600" x-show="gameState === 'preparing'">
                    Click when your team is ready to start the challenge
                </p>
                <p class="text-sm text-gray-600" x-show="gameState !== 'preparing'">
                    Game is <span x-text="gameState"></span>
                </p>
            </div>
        </div>
    @endif

    <!-- Game State -->
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
        
        <div x-show="gameState === 'preparing'" class="mt-4 text-gray-600">
            ⏳ Waiting for the game to start...
        </div>
        <div x-show="gameState === 'stopped'" class="mt-4 text-gray-600">
            🛑 Game has been stopped by the admin.
        </div>
    </div>

    <!-- Challenge Section (only shown when game is running) -->
    <div x-show="gameState === 'running' && cipherText" class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-2xl font-semibold mb-4">🔐 Your Challenge</h2>
        
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Encrypted Text:</h3>
            <div class="bg-gray-100 p-4 rounded-md font-mono text-lg break-words">
                <span x-text="cipherText">{{ $team->cipher_text }}</span>
            </div>
            <p class="mt-2 text-sm text-gray-600">
                💡 Hint: This text has been encrypted using a Caesar cipher. Try different shift values to decode it!
            </p>
        </div>

        <!-- Solution Submission -->
        <div x-show="!isCorrect">
            <h3 class="text-lg font-semibold mb-2">Submit Your Solution:</h3>
            <form action="{{ route('team.solution.submit', $team->slug) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <textarea 
                        name="solution" 
                        rows="4" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter the decrypted text here..."
                        required></textarea>
                </div>
                <button 
                    type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md font-semibold">
                    Submit Solution
                </button>
            </form>
        </div>

        <!-- Success Message -->
        <div x-show="isCorrect" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            <strong class="font-bold">🎉 Congratulations!</strong>
            <span class="block sm:inline">You've successfully solved the Caesar cipher challenge!</span>
        </div>
    </div>

    <!-- Waiting Message -->
    <div x-show="gameState === 'running' && !cipherText" class="bg-yellow-50 border border-yellow-200 rounded-lg shadow-md p-6 mb-6">
        <p class="text-lg text-yellow-800">
            ⏳ You need to mark your team as "Ready" before the game starts to receive the challenge.
        </p>
    </div>
</div>

<script>
    function teamPage() {
        return {
            teamName: '{{ $team->name }}',
            isReady: {{ $team->is_ready ? 'true' : 'false' }},
            cipherText: '{{ $team->cipher_text }}',
            isCorrect: {{ $team->is_correct ? 'true' : 'false' }},
            gameState: '{{ $gameState->state }}',
            
            init() {
                // Poll for updates every 2 seconds
                setInterval(() => {
                    this.fetchUpdates();
                }, 2000);
            },
            
            async fetchUpdates() {
                try {
                    const response = await fetch('{{ route('team.updates', $team->slug) }}');
                    const data = await response.json();
                    
                    this.teamName = data.team.name || '{{ $team->slug }}';
                    this.isReady = data.team.is_ready;
                    this.cipherText = data.team.cipher_text || '';
                    this.isCorrect = data.team.is_correct;
                    this.gameState = data.game_state.state;
                } catch (error) {
                    console.error('Failed to fetch updates:', error);
                }
            }
        }
    }
</script>
@endsection

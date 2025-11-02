@extends('layouts.app')

@section('title', 'Team ' . ($team->name ?? $team->slug) . ' - Caesar Challenge')

@section('content')
<div class="max-w-5xl mx-auto" x-data="teamPage()">
    <!-- Team Header -->
    <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-2xl shadow-2xl p-8 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-2">Team Portal</h1>
                <p class="text-purple-100 font-mono">ID: {{ $team->slug }}</p>
            </div>
            <div class="bg-white/20 backdrop-blur-sm p-4 rounded-xl">
                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Team Name Setup -->
    @if(!$team->name)
        <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl shadow-2xl p-8 mb-8 text-white animate-fade-in">
            <div class="flex items-center space-x-3 mb-6">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
                <h2 class="text-2xl font-bold">👥 Set Your Team Name</h2>
            </div>
            <form action="{{ route('team.name.set', $team->slug) }}" method="POST">
                @csrf
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                    <input 
                        type="text" 
                        name="name" 
                        placeholder="Enter your team name..." 
                        class="flex-1 px-6 py-4 bg-white/20 backdrop-blur-sm border-2 border-white/30 rounded-xl text-white placeholder-white/70 focus:outline-none focus:ring-4 focus:ring-white/50 focus:border-white transition-all duration-200"
                        required
                        maxlength="100">
                    <button 
                        type="submit" 
                        class="bg-white text-purple-600 px-8 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                        Set Name
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 border-2 border-purple-100">
            <div class="flex items-center space-x-4">
                <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-4 rounded-xl text-white">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-semibold">Your Team Name</p>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent" x-text="teamName">{{ $team->name }}</h2>
                </div>
            </div>
        </div>
    @endif

    <!-- Ready Status -->
    @if($team->name)
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 border-2 border-gray-100 hover:shadow-2xl transition-shadow duration-300">
            <div class="flex items-center space-x-3 mb-6">
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-3 rounded-xl text-white">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Ready Status</h2>
                    <p class="text-gray-500">Mark when your team is ready to compete</p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <form action="{{ route('team.ready.toggle', $team->slug) }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    <button 
                        type="submit"
                        class="w-full sm:w-auto px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200"
                        :class="isReady ? 'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white' : 'bg-gray-300 hover:bg-gray-400 text-gray-700'"
                        :disabled="gameState !== 'preparing'"
                        x-bind:disabled="gameState !== 'preparing'">
                        <span x-show="isReady" class="flex items-center justify-center space-x-2">
                            <span>✓</span>
                            <span>Ready!</span>
                        </span>
                        <span x-show="!isReady" class="flex items-center justify-center space-x-2">
                            <span>Mark as Ready</span>
                        </span>
                    </button>
                </form>
                <p class="text-sm text-gray-600" x-show="gameState === 'preparing'">
                    Click when your team is ready to start the challenge
                </p>
                <p class="text-sm text-gray-600" x-show="gameState !== 'preparing'">
                    Game is <span class="font-semibold" x-text="gameState"></span>
                </p>
            </div>
        </div>
    @endif

    <!-- Game Status -->
    <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 border-2 border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
                <div class="bg-gradient-to-br from-blue-500 to-purple-600 p-3 rounded-xl text-white">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Game Status</h2>
                </div>
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
        
        <div x-show="gameState === 'preparing'" class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-6 text-yellow-800">
            <div class="flex items-center space-x-3">
                <span class="text-3xl">⏳</span>
                <p class="text-lg font-semibold">Waiting for the game to start...</p>
            </div>
        </div>
        <div x-show="gameState === 'stopped'" class="bg-red-50 border-2 border-red-200 rounded-xl p-6 text-red-800">
            <div class="flex items-center space-x-3">
                <span class="text-3xl">🛑</span>
                <p class="text-lg font-semibold">Game has been stopped by the admin.</p>
            </div>
        </div>
    </div>

    <!-- Challenge Section -->
    <div x-show="gameState === 'running' && cipherText" class="bg-gradient-to-br from-white to-purple-50 rounded-2xl shadow-2xl p-8 mb-8 border-2 border-purple-100 animate-fade-in">
        <div class="flex items-center space-x-3 mb-8">
            <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-4 rounded-xl text-white">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <h2 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">🔐 Your Challenge</h2>
                <p class="text-gray-600">Decrypt the Caesar cipher to win!</p>
            </div>
        </div>
        
        <div class="mb-8">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Encrypted Text:</h3>
            <div class="bg-gradient-to-r from-gray-900 to-gray-800 p-6 rounded-xl font-mono text-xl text-green-400 break-words shadow-xl border-2 border-gray-700">
                <span x-text="cipherText">{{ $team->cipher_text }}</span>
            </div>
            <div class="mt-4 bg-blue-50 border-2 border-blue-200 rounded-xl p-4">
                <p class="text-sm text-blue-800 flex items-start space-x-2">
                    <span class="text-2xl">💡</span>
                    <span><strong>Hint:</strong> This text has been encrypted using a Caesar cipher. Try different shift values to decode it!</span>
                </p>
            </div>
        </div>

        <!-- Solution Submission -->
        <div x-show="!isCorrect">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Submit Your Solution:</h3>
            <form action="{{ route('team.solution.submit', $team->slug) }}" method="POST">
                @csrf
                <div class="mb-6">
                    <textarea 
                        name="solution" 
                        rows="4" 
                        class="w-full px-6 py-4 border-2 border-purple-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-300 focus:border-purple-500 transition-all duration-200 font-mono text-lg"
                        placeholder="Enter the decrypted text here..."
                        required></textarea>
                </div>
                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                    <span class="flex items-center justify-center space-x-2">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>Submit Solution</span>
                    </span>
                </button>
            </form>
        </div>

        <!-- Success Message -->
        <div x-show="isCorrect" class="bg-gradient-to-r from-green-400 to-emerald-500 text-white px-8 py-6 rounded-2xl shadow-2xl flex items-center space-x-4 animate-fade-in">
            <span class="text-5xl">🎉</span>
            <div>
                <p class="text-2xl font-bold mb-1">Congratulations!</p>
                <p class="text-lg">You've successfully solved the Caesar cipher challenge!</p>
            </div>
        </div>
    </div>

    <!-- Waiting Message -->
    <div x-show="gameState === 'running' && !cipherText" class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white rounded-2xl shadow-xl p-8 mb-8 animate-fade-in">
        <div class="flex items-center space-x-4">
            <span class="text-5xl">⏳</span>
            <div>
                <p class="text-xl font-bold mb-2">Almost Ready!</p>
                <p class="text-lg">You need to mark your team as "Ready" before the game starts to receive the challenge.</p>
            </div>
        </div>
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

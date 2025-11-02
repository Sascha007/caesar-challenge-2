<?php

namespace Tests\Feature;

use App\Models\GameState;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'GameStateSeeder']);
    }

    public function test_admin_can_create_teams(): void
    {
        $response = $this->post(route('admin.teams.create'), [
            'count' => 3
        ]);

        $response->assertRedirect(route('admin.index'));
        $this->assertDatabaseCount('teams', 3);
    }

    public function test_team_can_set_name(): void
    {
        $team = Team::create(['slug' => 'test123']);

        $response = $this->post(route('team.name.set', $team->slug), [
            'name' => 'Team Alpha'
        ]);

        $response->assertRedirect(route('team.show', $team->slug));
        $this->assertDatabaseHas('teams', [
            'slug' => 'test123',
            'name' => 'Team Alpha'
        ]);
    }

    public function test_team_can_toggle_ready_status(): void
    {
        $team = Team::create(['slug' => 'test123', 'name' => 'Team Alpha', 'is_ready' => false]);

        $this->assertFalse($team->is_ready);

        $this->post(route('team.ready.toggle', $team->slug));
        
        $team->refresh();
        $this->assertTrue($team->is_ready);
    }

    public function test_admin_can_start_game(): void
    {
        $gameState = GameState::current();
        $gameState->challenge_text = 'Hello World';
        $gameState->save();

        $team = Team::create([
            'slug' => 'test123',
            'name' => 'Team Alpha',
            'is_ready' => true
        ]);

        $response = $this->post(route('admin.game.start'));

        $response->assertRedirect(route('admin.index'));
        
        $gameState->refresh();
        $this->assertEquals('running', $gameState->state);

        $team->refresh();
        $this->assertNotNull($team->cipher_text);
        $this->assertNotNull($team->shift);
    }

    public function test_team_can_submit_correct_solution(): void
    {
        $gameState = GameState::current();
        $gameState->state = 'running';
        $gameState->challenge_text = 'Hello World';
        $gameState->save();

        $team = Team::create([
            'slug' => 'test123',
            'name' => 'Team Alpha',
            'is_ready' => true,
            'cipher_text' => 'Encoded text',
            'shift' => 3
        ]);

        $response = $this->post(route('team.solution.submit', $team->slug), [
            'solution' => 'Hello World'
        ]);

        $team->refresh();
        $this->assertTrue($team->is_correct);
        $this->assertNotNull($team->completed_at);
    }

    public function test_ranking_page_shows_teams(): void
    {
        Team::create([
            'slug' => 'test1',
            'name' => 'Team Alpha',
            'is_ready' => true,
            'is_correct' => true,
            'completed_at' => now()
        ]);

        Team::create([
            'slug' => 'test2',
            'name' => 'Team Beta',
            'is_ready' => true,
            'is_correct' => false
        ]);

        $response = $this->get(route('ranking.index'));

        $response->assertStatus(200);
        $response->assertSee('Team Alpha');
        $response->assertSee('Team Beta');
    }
}

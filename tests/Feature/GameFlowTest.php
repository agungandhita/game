<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameFlowTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true; // Run seeders

    public function test_full_game_flow(): void
    {
        // 1. Register
        $response = $this->post('/register', [
            'nickname' => 'BudiJuara123',
            'grade' => 3,
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        // 2. Dashboard
        $response = $this->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dunia Angka');

        // 3. Enter World
        $response = $this->get('/world/dunia-angka');
        $response->assertStatus(200);
        $response->assertSee('Level 1');

        // 4. Enter Level
        $level = \App\Models\Level::first();
        $response = $this->get('/level/'.$level->id);
        $response->assertStatus(200);

        // 5. Submit Answer
        // Get correct answers
        $questions = $level->questions;
        $answers = [];
        foreach ($questions as $q) {
            $correctOption = $q->options()->where('is_correct', true)->first();
            $answers[$q->id] = $correctOption->id;
        }

        $response = $this->postJson('/level/'.$level->id.'/submit', [
            'answers' => $answers,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'is_completed' => true,
                'stars' => 3,
            ]);

        // Check DB
        $this->assertDatabaseHas('user_progress', [
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'level_id' => $level->id,
            'is_completed' => true,
            'stars' => 3,
        ]);
    }
}

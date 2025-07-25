<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Board;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BoardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_board()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $payload = [
            'title' => 'New Board'
        ];

        $response = $this->actingAs($admin)->postJson('/api/create-board', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'New Board']);
    }

    public function test_non_admin_cannot_create_board()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->postJson('/api/create-board', []);

        $response->assertStatus(403);
    }

    public function test_admin_can_update_board()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $board = Board::factory()->create();

        $response = $this->actingAs($admin)->putJson("/api/update-board/{$board->id}", [
            'title' => 'Updated'
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Updated']);
    }

    public function test_non_admin_cannot_update_board()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $board = Board::factory()->create();

        $response = $this->actingAs($user)->putJson("/api/update-board/{$board->id}", [
            'title' => 'Updated'
        ]);

        $response->assertStatus(403);
    }

    public function test_users_sees_all_boards(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $board1 = Board::factory()->create();
        $board2 = Board::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/boards');

        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => $board1->title]);
        $response->assertJsonFragment(['title' => $board2->title]);
    }


    public function test_admin_can_delete_board(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $board = Board::factory()->create();

        $response = $this->actingAs($admin)->deleteJson("/api/delete-board/{$board->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('boards', ['id' => $board->id]);
    }
}

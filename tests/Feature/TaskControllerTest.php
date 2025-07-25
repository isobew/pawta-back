<?php
namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\Models\Board;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_task()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $assignee = User::factory()->create(['is_admin' => false]);
        $board = Board::factory()->create();

        $payload = [
            'title' => 'New Task',
            'description' => 'Description',
            'status' => 'to-do',
            'due_date' => now()->addDays(3)->toDateString(),
            'assignee_id' => $assignee->id,
            'board_id' => $board->id,
            'creator_id' => $admin->id,
        ];

        $response = $this->actingAs($admin)->postJson('/api/create-task', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'New Task']);
    }

    public function test_non_admin_cannot_create_task()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->postJson('/api/create-task', []);

        $response->assertStatus(403);
    }

    public function test_users_can_update_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();

        $response = $this->actingAs($user)->putJson("/api/update-task/{$task->id}", [
            'title' => 'Updated',
            'description' => 'New description',
            'status' => 'in-progress',
            'due_date' => now()->addDays(4)->toDateString(),
            'assignee_id' => $task->assignee_id,
            'board_id' => $task->board_id,
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Updated']);
    }

    public function test_user_sees_only_their_tasks()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $other = User::factory()->create(['is_admin' => false]);

        Task::factory()->create(['assignee_id' => $user->id]);
        Task::factory()->create(['assignee_id' => $other->id]);

        $response = $this->actingAs($user)->getJson('/api/tasks');

        $response->assertStatus(200);
        $response->assertJsonMissing(['assignee_id' => $other->id]);
        $response->assertJsonFragment(['assignee_id' => $user->id]);
    }

    public function test_admin_sees_all_tasks(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Task::factory()->create(['assignee_id' => $user1->id]);
        Task::factory()->create(['assignee_id' => $user2->id]);

        $response = $this->actingAs($admin)->getJson('/api/tasks');

        $response->assertStatus(200);
        $response->assertJsonFragment(['assignee_id' => $user1->id]);
        $response->assertJsonFragment(['assignee_id' => $user2->id]);
    }

    public function test_users_can_delete_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();

        $response = $this->actingAs($user)->deleteJson("/api/delete-task/{$task->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

}

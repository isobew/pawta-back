<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_users()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        User::factory()->count(5)->create();

        $response = $this->actingAs($admin)->getJson('/api/users');

        $response->assertStatus(200);
        $response->assertJsonCount(6);
    }

    public function test_non_admin_cannot_list_users()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->getJson('/api/users');

        $response->assertStatus(403);
    }

    public function test_admin_can_search_users()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        User::factory()->create(['name' => 'John Doe']);
        User::factory()->create(['name' => 'Jane Doe']);

        $response = $this->actingAs($admin)->getJson('/api/users-list?search=Jane');

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Jane Doe']);
        $response->assertJsonMissing(['name' => 'John Doe']);
    }

    public function test_admin_can_update_user()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();

        $payload = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'is_admin' => true,
        ];

        $response = $this->actingAs($admin)->putJson("/api/update-user/{$user->id}", $payload);

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Updated Name']);
    }

    public function test_non_admin_cannot_update_user()
    {
        $user = User::factory()->create();
        $target = User::factory()->create();

        $response = $this->actingAs($user)->putJson("/api/update-user/{$target->id}", [
            'name' => 'New user',
            'email' => 'new_user@example.com',
            'is_admin' => true,
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_delete_user()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->deleteJson("/api/delete-user/{$user->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_non_admin_cannot_delete_user()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $target = User::factory()->create();

        $response = $this->actingAs($user)->deleteJson("/api/delete-user/{$target->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['id' => $target->id]);
    }
}

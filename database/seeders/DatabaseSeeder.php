<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Board;
use App\Models\Task;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@user.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        $board = Board::create([
            'title' => 'Board 1'
        ]);

        Task::create([
            'title' => 'Task 1',
            'description' => 'Task description',
            'status' => 'to-do',
            'board_id' => $board->id,
            'creator_id' => $admin->id,
            'assignee_id' => $user->id,
        ]);
    }
}

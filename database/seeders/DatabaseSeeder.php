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

        for ($i = 1; $i <= 20; $i++) {
            $board = Board::create([
                'title' => "Board $i"
            ]);

            for ($j = 1; $j <= 3; $j++) {
                Task::create([
                    'title' => "Task {$j} for Board {$i}",
                    'description' => "Task {$j} description for Board {$i}",
                    'status' => 'to-do',
                    'due_date' => now()->addDays($j),
                    'board_id' => $board->id,
                    'creator_id' => $admin->id,
                    'assignee_id' => $user->id,
                ]);
            }
        }
    }
}

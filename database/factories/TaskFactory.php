<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use App\Models\Board;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => 'to-do',
            'due_date' => now()->addDays(rand(1, 10)),
            'assignee_id' => User::factory(),
            'creator_id' => User::factory(),
            'board_id' => Board::factory(),
        ];
    }
}

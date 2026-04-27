<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $categories = Category::where('user_id', $user->id)->get();

            Task::create([
                'title' => 'Complete project report',
                'description' => 'Finish the quarterly report',
                'status' => 'en cours',
                'due_date' => now()->addDays(3),
                'user_id' => $user->id,
                'category_id' => $categories->where('name', 'Work')->first()->id,
            ]);

            Task::create([
                'title' => 'Buy groceries',
                'description' => 'Milk, bread, eggs, vegetables',
                'status' => 'à faire',
                'due_date' => now()->addDay(),
                'user_id' => $user->id,
                'category_id' => $categories->where('name', 'Shopping')->first()->id,
            ]);

            Task::create([
                'title' => 'Call dentist',
                'description' => 'Schedule dental appointment',
                'status' => 'à faire',
                'due_date' => now()->addDays(7),
                'user_id' => $user->id,
                'category_id' => $categories->where('name', 'Personal')->first()->id,
            ]);

            Task::create([
                'title' => 'Review code',
                'description' => 'Review pull requests from team',
                'status' => 'terminé',
                'due_date' => now()->subDays(1),
                'user_id' => $user->id,
                'category_id' => $categories->where('name', 'Work')->first()->id,
            ]);

            Task::create([
                'title' => 'Exercise',
                'description' => 'Go for a 30-minute run',
                'status' => 'à faire',
                'due_date' => now(),
                'user_id' => $user->id,
                'category_id' => $categories->where('name', 'Personal')->first()->id,
            ]);
        }
    }
}
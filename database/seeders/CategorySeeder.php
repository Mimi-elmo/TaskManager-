<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            Category::create([
                'name' => 'Work',
                'description' => 'Work-related tasks',
                'user_id' => $user->id,
            ]);

            Category::create([
                'name' => 'Personal',
                'description' => 'Personal tasks',
                'user_id' => $user->id,
            ]);

            Category::create([
                'name' => 'Shopping',
                'description' => 'Shopping list',
                'user_id' => $user->id,
            ]);
        }
    }
}
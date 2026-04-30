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
        $defaultCategories = ['Work', 'Personal', 'Shopping'];

        foreach ($users as $user) {
            foreach ($defaultCategories as $categoryName) {
                Category::firstOrCreate([
                    'name' => $categoryName,
                    'user_id' => $user->id,
                ], [
                    'description' => $categoryName . '-related tasks',
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
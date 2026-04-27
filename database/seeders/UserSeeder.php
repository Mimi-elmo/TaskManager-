<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Abderrahmane',
            'email' => 'abderrahmane@example.com',
            'password' => bcrypt('password123'),
        ]);

        User::create([
            'name' => 'Ahmed',
            'email' => 'ahmed@example.com',
            'password' => bcrypt('password123'),
        ]);

        User::create([
            'name' => 'Fatima',
            'email' => 'fatima@example.com',
            'password' => bcrypt('password123'),
        ]);
    }
}
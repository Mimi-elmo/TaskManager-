<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'ilyas@mail.ma'],
            ['name' => 'ilyas', 'password' => bcrypt('password')]
        );
    }
}
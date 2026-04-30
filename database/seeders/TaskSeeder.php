<?php
namespace Database\Seeders;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'ilyas@mail.ma')->first();

        $work = Category::firstOrCreate(['name' => 'Travail', 'user_id' => $user->id]);
        $personal = Category::firstOrCreate(['name' => 'Personnel', 'user_id' => $user->id]);

        $tasks = [
            ['title' => 'Finaliser le rapport mensuel', 'description' => 'Compiler les stats et envoyer au manager', 'status' => 'en cours', 'due_date' => now()->addDays(5)],
            ['title' => 'Revision du code', 'description' => 'Code review des PR en attente', 'status' => 'à faire', 'due_date' => now()->addDays(2)],
            ['title' => 'Deployer la nouvelle version', 'status' => 'à faire'],
            ['title' => 'Repondre aux emails', 'status' => 'terminé'],
            ['title' => 'Courses hebdomadaires', 'description' => 'Legumes, fruits et produits laitiers', 'status' => 'à faire', 'due_date' => now()->addDay()],
            ['title' => 'Salle de sport', 'status' => 'en cours'],
            ['title' => 'Lire un livre', 'description' => 'Finir le roman en cours', 'status' => 'à faire'],
        ];

        foreach ($tasks as $task) {
            Task::firstOrCreate(
                ['title' => $task['title'], 'user_id' => $user->id],
                array_merge($task, ['user_id' => $user->id, 'category_id' => $work->id])
            );
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;

class EventSeeder extends Seeder
{
    public function run()
    {
        // Находим пользователя с именем "Test User"
        $user = User::where('name', 'Test User')->first();

        // Если пользователь найден, создаём события
        if ($user) {
            Event::factory()
                ->count(10)
                ->state(['user_id' => $user->id])
                ->create();
        }
    }
}

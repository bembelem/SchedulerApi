<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(['practice', 'lecture']),
            'room' => $this->faker->numberBetween(100, 500),
            'teacher' => $this->faker->name,
            'name' => $this->faker->randomElement(['Дифури', 'МАтанал', 'анжумания', 'поутягиваня']),
            'order' => $this->faker->numberBetween(1, 8),
            'date' => Carbon::now()->addDays($this->faker->numberBetween(0, 5))->format('Y-m-d'),
            'homework' => $this->faker->randomElement(['Кричать', 'Умолять', 'Сдаваться', 'Сидеть']),
            'notes' => $this->faker->text,
            'test' => $this->faker->boolean,
            'project' => $this->faker->boolean,
            'colloquium' => $this->faker->boolean,
            'user_id' => null, // Установка user_id позже через state()
        ];
    }
}

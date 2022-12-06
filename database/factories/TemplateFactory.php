<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TemplateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'body' => [[123.54,"numero"],["dog","text"]],
            'states_id' => $this->faker->numberBetween(1, 2),
            'user_id' => $this->faker->numberBetween(1, User::count()),
        ];
    }
}

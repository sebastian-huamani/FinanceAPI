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
            'body' =>  [ 'name',  'mount', 'count', 'data'],
            'state' => $this->faker->numberBetween(0,1),
            'user_id' => $this->faker->numberBetween(1, User::count()),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'body' => [["name","canela"], ["test","135.99"], ["count","as"], ["col3", "cat"]],
            'amount' => $this->faker->randomFloat(2, -400, 1600),
            'template_id' => $this->faker->numberBetween(1, Template::count()),
        ];
    }
}

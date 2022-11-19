<?php

namespace Database\Factories;

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
            'title' => $this->faker->word(),
            'body' => '{"name" : "canel", "mount": "1999.55", "count" : "as", "col3": "dad"}',
            'amount' => $this->faker->randomFloat(2, -400, 1600),
        ];
    }
}

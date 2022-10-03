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
            'body' => ['col1' , 'name','col2', 'mount', 'col3', 'count','col4', 'data'],
            'amount' => $this->faker->randomFloat(2, 400, 1600),

        ];
    }
}

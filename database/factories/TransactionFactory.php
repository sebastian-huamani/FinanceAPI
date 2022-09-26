<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // $countN = Count::count();
        // $itemsN = Item::count(); 

        return [
            // 'cards_id' => $this->faker->unique()->numberBetween(1,30),
            // 'items_id' => $this->faker->numberBetween(1, $itemsN),
        ];
    }
}

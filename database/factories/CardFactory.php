<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $dateCardsId = [null, null];
        $item = $dateCardsId[array_rand($dateCardsId)];

        return [
            'name' => $this->faker->word(),
            'bottom_line' => $this->faker->randomFloat(2, -400, 1600),
            'name_banck' => $this->faker->word(),
            'card_expiration_date' => $this->faker->date('Y_m_d'),
            'type_cards_id' => $this->faker->randomElement([1, 2]),
            'date_cards_id' => null,
            'user_id' => $this->faker->numberBetween(1, User::count()),
        ];
    }
}

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
            'type_card_id' => 1,
            'date_card_id' => null,
            'state_id' => 1,
            'user_id' => $this->faker->numberBetween(1, User::count()),
        ];
    }
}

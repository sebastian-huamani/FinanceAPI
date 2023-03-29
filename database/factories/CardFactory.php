<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CardFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'bottom_line' => 0,
            'amount' => $this->faker->randomFloat(2, -400, 1600),
            'name_banck' => $this->faker->word(),
            'card_expiration_date' => $this->faker->date('Y_m_d'),
            'type_card_id' => 1,
            'date_card_id' => null,
            'state_id' => 1,
            'user_id' => 1,
            'color_id' => 1
        ];
    }
}

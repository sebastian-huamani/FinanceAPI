<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Color>
 */
class ColorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'color' => $this->faker->word(),
            'code_top' => $this->faker->safeHexColor(),
            'code_bottom' => $this->faker->safeHexColor(),
            'code_type' => $this->faker->safeHexColor(),
            'code_button' => $this->faker->safeHexColor()
        ];
    }
}

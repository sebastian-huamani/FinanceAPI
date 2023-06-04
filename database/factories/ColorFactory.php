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
            'color_panel_top' => $this->faker->safeHexColor(),
            'color_panel_bottom' => $this->faker->safeHexColor(),
            'color_type' => $this->faker->safeHexColor(),
            'color_button' => $this->faker->safeHexColor()
        ];
    }
}

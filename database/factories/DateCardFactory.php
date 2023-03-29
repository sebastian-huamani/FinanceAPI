<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DateCard>
 */
class DateCardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $billing_cycle = $this->faker->numberBetween(1, 30);
        $closing_date = $billing_cycle;
        $payment_due_date = $billing_cycle = $this->faker->numberBetween(1, 30);


        return [
            'billing_cycle' =>  $billing_cycle,
            'closing_date' => $closing_date,
            'payment_due_date' => $payment_due_date,
            'user_id' => 1,
        ];
    }
}
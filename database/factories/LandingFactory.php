<?php

namespace Database\Factories;

use App\Models\Card;
use App\Models\User;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Landing>
 */
class LandingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $amount = fake()->randomFloat(2, 200, 600);
        $list = [];
        for ($i=0; $i < 4; $i++) { 
            $randomDays = rand(15, 30);
            $lastValue = round($amount - fake()->randomFloat(2, 20, 80), 2);
            $changeDate = Carbon::now(new DateTimeZone('America/Lima'))->subDays($randomDays);
            $paymentDate = Carbon::now(new DateTimeZone('America/Lima'))->subDays($randomDays);
            array_push($list, [$changeDate, $lastValue , $paymentDate]);
        }

        return [
            'amount' => $amount,
            'created_date_lending' => fake()->dateTimeBetween('-12 week', 'now'),
            'payment_date_lending' => fake()->dateTimeBetween('now', '+12 week'),
            'debtor' => fake()->userName(),
            'user_id' => fake()->numberBetween(1, User::count()),
            'state_id' => fake()->randomElement([1, 2]),
            'postpone' => fake()->randomElement([ [] , $list ])
        ];
    }
}

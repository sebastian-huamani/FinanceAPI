<?php

namespace Database\Factories;

use App\Models\DataInfoUser;
use App\Models\User;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataInfoUser>
 */
class DataInfoUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        // $full_credit = [];
        // $aviable_credit = [];
        // $full_debit = [];
        // $aviable_debit = [];
        
        // for ($i=0; $i < 13; $i++) { 
            
          
        //     $amount = $this->faker->randomFloat(2, 1600, 4261 );
        //     $dateNow = Carbon::now(new DateTimeZone('America/Lima'));
        //     array_push($full_credit, [$dateNow->addMonths($i), $amount]);
            
        //     $amount = $this->faker->randomFloat(2, 1600, 4261 );
        //     $dateNow = Carbon::now(new DateTimeZone('America/Lima'));
        //     array_push($aviable_credit, [$dateNow->addMonths($i), $amount]);

        //     $amount = $this->faker->randomFloat(2, 1600, 4261 );
        //     $dateNow = Carbon::now(new DateTimeZone('America/Lima'));
        //     array_push($full_debit, [$dateNow->addMonths($i), $amount]);

        //     $amount = $this->faker->randomFloat(2, 1600, 4261 );
        //     $dateNow = Carbon::now(new DateTimeZone('America/Lima'));
        //     array_push($aviable_debit, [$dateNow->addMonths($i), $amount]);

        // }

        

        return [
            // 'full_credit' => $full_credit,
            // 'aviable_credit' => $aviable_credit,
            // 'full_debit' => $full_debit,
            // 'aviable_debit' => $aviable_debit,
            // 'user_id' => $this->faker->numberBetween(1, User::count()),
        ];
    }
}

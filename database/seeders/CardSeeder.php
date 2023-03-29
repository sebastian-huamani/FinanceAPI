<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\DateCard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
   
    public function run()
    {
        $dateCards =  DateCard::factory(1)->create();
        
        foreach ($dateCards as $dateCard) {

            $bottomLine = fake()->randomFloat(2, 1300, 1600);

            Card::factory(1)->create([
                'name' => fake()->word(),
                'bottom_line' => $bottomLine,
                'amount' => $bottomLine - fake()->randomFloat(2, 200, 399),
                'name_banck' => fake()->word(),
                'card_expiration_date' => fake()->date('Y_m_d'),
                'type_card_id' => 2,
                'date_card_id' => $dateCard->id,
                'state_id' => 1,
                'user_id' => $dateCard->user_id,
                'color_id' => fake()->randomFloat(1, 8),
            ]);
        }

        Card::factory(2)->create();
    }
}

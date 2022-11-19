<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\DateCard;
use App\Models\State;
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
        State::factory(1)->create([
            'name' => 'Activo'
        ]);
        State::factory(1)->create([
            'name' => 'Desactivado'
        ]);
        

        $dateCards =  DateCard::factory(15)->create();
        
        foreach ($dateCards as $dateCard) {

            Card::factory(1)->create([
                'name' => fake()->word(),
                'bottom_line' => fake()->randomFloat(2, -400, 1600),
                'name_banck' => fake()->word(),
                'card_expiration_date' => fake()->date('Y_m_d'),
                'type_cards_id' => 2,
                'date_cards_id' => $dateCard->id,
                'states_id' => 1,
                'user_id' => $dateCard->user_id,
            ]);
        }

        Card::factory(15)->create();
    }
}

<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */ 
    public function run()
    {
        $items = Item::factory(60)->create();
        $countNTotal = Card::count();

        foreach( $items as $item) {

            Transaction::factory(1)->create([
                'cards_id' => rand(1, $countNTotal),
                'items_id' => $item->id
            ]);

        }

    }
}

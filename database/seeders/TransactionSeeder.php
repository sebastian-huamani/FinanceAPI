<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Item;
use App\Models\Template;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Generator;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        $items = Item::factory(120)->create();

        foreach( $items as $item) {

            Transaction::factory(1)->create([
                'cards_id' => rand(1, Card::count()),
                'items_id' => $item->id
            ]);

        }

    }
}

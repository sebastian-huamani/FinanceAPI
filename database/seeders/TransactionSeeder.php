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
        
        $cards = Card::get();
        foreach( $cards as $card){

            $template = rand(1, 150);
            $amount = rand(-1000, 1000);

            $card->items()->create(['title'=> 'et occaecati voluptas', 'body' => [["name","canela"], ["test","135.99"], ["count","as"], ["col3", "cat"]],'amount'=> $amount ,'template_id' => $template]);

            
            $template = rand(1, 150);
            $amount = rand(-1000, 1000);

            $card->items()->create(['title'=> 'et voluptas occaecati', 'body' => [["name","canela"], ["test","135.99"], ["count","as"], ["col3", "cat"]],'amount'=> $amount ,'template_id' => $template]);


            $template = rand(1, 150);
            $amount = rand(-1000, 1000);

            $card->items()->create(['title'=> 'voluptas occaecati et', 'body' => [["name","canela"], ["test","135.99"], ["count","as"], ["col3", "cat"]],'amount'=> $amount ,'template_id' => $template]);
        }

    }
}

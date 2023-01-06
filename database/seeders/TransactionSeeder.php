<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Item;
use App\Models\Template;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Database\Seeder;
use Faker\Generator;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        
        $cards = Card::get();
        foreach( $cards as $card){

            $toDay = Carbon::now(new DateTimeZone('America/Lima'));

            for ($i=0; $i < 80; $i++) { 

                $template = rand(1, 150);
                $amount = rand(-1000, 1000);
    
                $card->items()->create(['title'=> 'voluptas occaecati et', 'body' => [["name","canela"], ["test","135.99"], ["count","as"], ["col3", "cat"]],'amount'=> $amount ,'template_id' => $template, 'created_at' => $toDay->addDays(1)]);
            }


        }

    }
}

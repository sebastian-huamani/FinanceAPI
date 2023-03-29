<?php

namespace Database\Seeders;

use App\Models\Card;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Database\Seeder;
use Faker\Generator;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        $cards = Card::where('user_id', 1)->get();
        foreach ($cards as $card) {
            $toDay = Carbon::now(new DateTimeZone('America/Lima'));

            for ($i = 0; $i < 80; $i++) {
                $amount = rand(-100000, 100000) / 100;

                $card->items()->create([
                    'title' => 'voluptas occaecati et',
                    'body' => [["restaurant","The kilo G","text"]],
                    'amount' => $amount,
                    'template_id' => 2,
                    'created_at' => $toDay->subDays(1),
                ]);
            }
        }
    }
}

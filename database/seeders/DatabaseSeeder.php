<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Template;
use App\Models\TypeCard;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(3)->create();
        Template::factory(10)->create();
        TypeCard::factory(2)->create();
        
        $this->call(CardSeeder::class);

        $this->call(TransactionSeeder::class);
    }
}

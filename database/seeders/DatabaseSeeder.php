<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\State;
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

        State::factory(1)->create([
            'name' => 'Activo'
        ]);
        State::factory(1)->create([
            'name' => 'Desactivado'
        ]);
        
        
        Template::factory(100)->create();

        TypeCard::factory(1)->create([
            'name' => 'Debit'
        ]);

        TypeCard::factory(1)->create([
            'name' => 'Credit'
        ]);
        
        $this->call(CardSeeder::class);

        $this->call(TransactionSeeder::class);
    }
}

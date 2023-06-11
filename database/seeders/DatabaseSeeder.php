<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\DataInfoUser;
use App\Models\Landing;
use App\Models\State;
use App\Models\Template;
use App\Models\TypeCard;
use App\Models\User;
use Carbon\Carbon;
use DateTimeZone;
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
        // $users = User::factory(1)->create();
        State::factory(1)->create([
            'name' => 'Activo'
        ]);
        State::factory(1)->create([
            'name' => 'Desactivado'
        ]);
        State::factory(1)->create([
            'name' => 'Pendiente'
        ]);


        // Template::factory(150)->create();

        TypeCard::factory(1)->create([
            'name' => 'Debit'
        ]);
        
        TypeCard::factory(1)->create([
            'name' => 'Credit'
        ]);

        Template::factory(1)->create([
            'title' => 'transferencia entre cuentas',
            'body' => [[123.54,"number"],["dog","text"]],
            'states_id' => 1,
            'user_id' => 1,
        ]);


        // foreach ($users as $user) {
        //     $dateNow = Carbon::now(new DateTimeZone('America/Lima'));
        //     for ($i=0; $i < 20; $i++) { 
        //         $user->data_info_user()->create([
        //             'full_credit' => fake()->randomFloat(2, 1456, 4251),
        //             'aviable_credit' => fake()->randomFloat(2, 1456, 4251),
        //             'full_debit' => fake()->randomFloat(2, 1456, 4251),
        //             'aviable_debit' => fake()->randomFloat(2, 1456, 4251),
        //             'user_id' => $user->id,
        //             'created_at' => $dateNow->subMonth()
        //         ]);
        //     }
        // }


        $this->call(ColorSeeder::class);
        // $this->call(TransactionSeeder::class);
        
        
        // Landing::factory(60)->create();
        

    }
}

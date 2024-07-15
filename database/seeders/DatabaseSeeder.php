<?php

namespace Database\Seeders;

use App\Models\Mouvement;
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
        \App\Models\User::factory()->create();
        //Mouvement::factory(10)->create();
    }
}

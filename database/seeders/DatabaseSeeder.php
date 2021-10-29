<?php

namespace Database\Seeders;

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
        \App\Models\User::factory(96)->create();
        \App\Models\Offer::factory(96)->create();
        \App\Models\Video::factory(990)->create();
        \App\Models\Order::factory(990)->create();
    }
}

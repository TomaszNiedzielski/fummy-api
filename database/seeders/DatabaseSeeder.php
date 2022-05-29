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
        \App\Models\User::factory(300)->create()->each(function ($user) {
            $offers = \App\Models\Offer::factory(3)->make();
            
            $user->offers()->save($offers[0]);
            $user->offers()->save($offers[1]);
            $user->offers()->save($offers[2]);
        });

        \App\Models\Order::factory(890)->create();
        \App\Models\Order::factory(890)->create();
        \App\Models\Order::factory(890)->create();
        \App\Models\Video::factory(2000)->create();
        \App\Models\Review::factory(1500)->create();
    }
}

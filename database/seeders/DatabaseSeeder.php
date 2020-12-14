<?php

namespace Database\Seeders;

class DatabaseSeeder extends ProductionSeeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(VideoSeeder::class);
    }
}

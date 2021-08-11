<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\category_seeder;
use Database\Seeders\banner_seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            category_seeder::class,
            banner_seeder::class,
        ]);
    }
}

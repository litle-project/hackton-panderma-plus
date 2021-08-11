<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class banner_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [ 'image' => 'banner-donor-1.jpeg', ],
            [ 'image' => 'banner-donor-2.jpeg', ],
            [ 'image' => 'banner-donor-3.jpeg', ],
        ];

        DB::table('banners')->insert($data);
    }
}

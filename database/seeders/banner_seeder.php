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
            [ 'image' => 'satu.png', ],
            [ 'image' => 'dua.png', ],
            [ 'image' => 'tiga.png', ],
        ];
        
        DB::table('banners')->truncate();
        DB::table('banners')->insert($data);
    }
}

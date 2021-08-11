<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class category_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Donor Darah',
                'icon' => 'blood.png',
            ],
            [
                'name' => 'Donor Plasma',
                'icon' => 'plasma.png',
            ],
            [
                'name' => 'Donor Ginjal',
                'icon' => 'kidney.png',
            ],
            [
                'name' => 'Donor Mata',
                'icon' => 'eye.png',
            ],
            [
                'name' => 'Donor Paru-paru',
                'icon' => 'lungs.png',
            ],
            [
                'name' => 'Donor Kulit',
                'icon' => 'skin.png',
            ],
            [
                'name' => 'Donor Alat Medis',
                'icon' => 'chair.png',
            ],
        ];

        DB::table('categories')->insert($data);
    }
}

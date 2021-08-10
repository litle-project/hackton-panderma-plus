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
                'icon' => url('/icon/blood.png'),
            ],
            [
                'name' => 'Donor Plasma',
                'icon' => url('/icon/plasma.png'),
            ],
            [
                'name' => 'Donor Ginjal',
                'icon' => url('/icon/kidney.png'),
            ],
            [
                'name' => 'Donor Mata',
                'icon' => url('/icon/eye.png'),
            ],
            [
                'name' => 'Donor Paru-paru',
                'icon' => url('/icon/lungs.png'),
            ],
            [
                'name' => 'Donor Kulit',
                'icon' => url('/icon/skin.png'),
            ],
            [
                'name' => 'Donor Alat Medis',
                'icon' => url('/icon/chair.png'),
            ],
        ];

        DB::table('categories')->insert($data);
    }
}

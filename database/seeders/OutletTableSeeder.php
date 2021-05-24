<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OutletTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('outlet')->delete();
        
        \DB::table('outlet')->insert(array (
            0 => 
            array (
                'ID_OUTLET' => '096d71cfc0824fc6919640a4eff6441b',
                'NAMA' => 'Surabaya',
                'created_at' => '2021-05-24 02:58:18',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'ID_OUTLET' => '7c0c73390fcd40c38b0e4a9807109b3a',
                'NAMA' => 'Malang',
                'created_at' => '2021-05-24 02:58:26',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
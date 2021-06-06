<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class UserTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('user')->delete();
        
        \DB::table('user')->insert(array (
            0 => 
            array (
                'ID_USER' => 'a247e02d86b244f6bf4aa15114bb1d92',
                'username' => 'admin',
                'password' => '$2y$10$SHgQGEXAn0r7s4A7F9ROe.vM4zN0avUAq1QvtZXkHdOUXpUCuNdz.',
                'NAMA' => 'Admin',
                'EMAIL' => 'admin@gmail.com',
                'NO_TELP' => '082317881411',
                'ROLE' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'ID_USER' => Uuid::uuid4()->getHex(),
                'username' => 'pic',
                'password' => Hash::make('pic'),
                'NAMA' => 'PIC Dummy',
                'EMAIL' => 'pic@gmail.com',
                'NO_TELP' => '082317881411',
                'ROLE' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
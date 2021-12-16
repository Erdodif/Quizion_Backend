<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TokenTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('token')->delete();
        
        DB::table('token')->insert(array (
            0 => 
            array (
                'id' => 5,
                'user_id' => 3,
                'token' => '31050884995806e0ff796ff0ebf59532368551877c7232289da4bcd7673b12fda474277adc798e0b636e43a07ac790bfe47c966719414c55dbc1a11842dbbf3d',
                'created_at' => '2021-12-09 21:45:07',
                'updated_at' => '2021-12-09 21:45:07',
            ),
            1 => 
            array (
                'id' => 6,
                'user_id' => 3,
                'token' => '82d2489ff2046af5ffe3fcd11cd5d60d1a2d3949c73b47fce8c6a2e91caa0833b15303e230c7e40fe899e5f423a25c0e2d3e19616db8247ddf8d7096d8a687dd',
                'created_at' => '2021-12-09 21:45:37',
                'updated_at' => '2021-12-09 21:45:37',
            ),
            2 => 
            array (
                'id' => 7,
                'user_id' => 3,
                'token' => 'a7d544150f5b666e18107cb02d45bfe81c4a92fd6f4bd65c64a84971b3d6d604a5e92540279c2a80a7437f808b0a4ff7c88c52cd04980dee13c54c30b2787220',
                'created_at' => '2021-12-09 21:45:41',
                'updated_at' => '2021-12-09 21:45:41',
            ),
            3 => 
            array (
                'id' => 8,
                'user_id' => 3,
                'token' => '9f6d51e44c1de4ef6867cdc8e48840a4da6dcbd1cfd6ff52e4bae223b740440ce798f1c5df36f1a29d3511ac61af1d230ed7e37b0dc741a3a731ea88717dc82d',
                'created_at' => '2021-12-09 21:46:03',
                'updated_at' => '2021-12-09 21:46:03',
            ),
            4 => 
            array (
                'id' => 9,
                'user_id' => 4,
                'token' => 'a9c023ec8110f23d1fa22553b5793e63a2e40cd58872498b274a7605ee313718e1b4e4648b51e237a2ac106fba64cd6d904bdfbf8eae674d1ed3e90dd6e23cbd',
                'created_at' => '2021-12-13 23:16:40',
                'updated_at' => '2021-12-13 23:16:40',
            ),
        ));
        
        
    }
}
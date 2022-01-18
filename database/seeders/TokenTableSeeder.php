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
                'id' => 1,
                'user_id' => 3,
                'token' => '31050884995806e0ff796ff0ebf59532368551877c7232289da4bcd7673b12fda474277adc798e0b636e43a07ac790bfe47c966719414c55dbc1a11842dbbf3d',
                'created_at' => '2021-12-09 21:45:07',
                'updated_at' => '2021-12-09 21:45:07',
            ),
            array (
                'id' => 2,
                'user_id' => 4,
                'token' => 'a9c023ec8110f23d1fa22553b5793e63a2e40cd58872498b274a7605ee313718e1b4e4648b51e237a2ac106fba64cd6d904bdfbf8eae674d1ed3e90dd6e23cbd',
                'created_at' => '2021-12-13 23:16:40',
                'updated_at' => '2021-12-13 23:16:40',
            ),
        ));


    }
}

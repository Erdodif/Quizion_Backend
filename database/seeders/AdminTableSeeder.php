<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('admin')->delete();

        DB::table('admin')->insert(array (
            0 =>
            array (
                'id' => 1,
                'user_id' => 4,
            ),
        ));
        DB::table('admin')->insert(array (
            0 =>
            array (
                'id' => 2,
                'user_id' => 5,
            ),
        ));


    }
}

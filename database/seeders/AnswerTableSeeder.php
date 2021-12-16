<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnswerTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('answer')->delete();
        
        DB::table('answer')->insert(array (
            0 => 
            array (
                'id' => 1,
                'question_id' => 1,
                'content' => 'Első kvíz első kérdés első válaszlehetőség',
                'is_right' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'question_id' => 1,
                'content' => 'Első kvíz első kérdés második válaszlehetőség',
                'is_right' => 0,
            ),
            2 => 
            array (
                'id' => 3,
                'question_id' => 1,
                'content' => 'Első kvíz első kérdés harmadik válaszlehetőség',
                'is_right' => 0,
            ),
            3 => 
            array (
                'id' => 4,
                'question_id' => 1,
                'content' => 'Első kvíz első kérdés negyedik válaszlehetőség',
                'is_right' => 0,
            ),
            4 => 
            array (
                'id' => 5,
                'question_id' => 3,
                'content' => 'Második kvíz első kérdés első válaszlehetőség',
                'is_right' => 1,
            ),
            5 => 
            array (
                'id' => 6,
                'question_id' => 3,
                'content' => 'Második kvíz első kérdés második válaszlehetőség',
                'is_right' => 0,
            ),
            6 => 
            array (
                'id' => 7,
                'question_id' => 3,
                'content' => 'Második kvíz első kérdés harmadik válaszlehetőség',
                'is_right' => 0,
            ),
            7 => 
            array (
                'id' => 8,
                'question_id' => 2,
                'content' => 'Első kvíz második kérdés első válaszlehetőség',
                'is_right' => 1,
            ),
            8 => 
            array (
                'id' => 9,
                'question_id' => 2,
                'content' => 'Első kvíz második kérdés második válaszlehetőség',
                'is_right' => 0,
            ),
            9 => 
            array (
                'id' => 10,
                'question_id' => 2,
                'content' => 'Első kvíz második kérdés harmadik válaszlehetőség',
                'is_right' => 0,
            ),
            10 => 
            array (
                'id' => 11,
                'question_id' => 4,
                'content' => 'Második kvíz második kérdés első válaszlehetőség',
                'is_right' => 1,
            ),
            11 => 
            array (
                'id' => 12,
                'question_id' => 4,
                'content' => 'Második kvíz második kérdés második válaszlehetőség',
                'is_right' => 0,
            ),
            12 => 
            array (
                'id' => 13,
                'question_id' => 4,
                'content' => 'Második kvíz második kérdés harmadik válaszlehetőség',
                'is_right' => 0,
            ),
            13 => 
            array (
                'id' => 14,
                'question_id' => 4,
                'content' => 'Második kvíz második kérdés negyedik válaszlehetőség',
                'is_right' => 0,
            ),
            14 => 
            array (
                'id' => 15,
                'question_id' => 5,
                'content' => 'Második kvíz harmadik kérdés első válaszlehetőség',
                'is_right' => 1,
            ),
            15 => 
            array (
                'id' => 16,
                'question_id' => 5,
                'content' => 'Második kvíz harmadik kérdés második válaszlehetőség',
                'is_right' => 0,
            ),
            16 => 
            array (
                'id' => 17,
                'question_id' => 6,
                'content' => 'Harmadik kvíz első kérdés első válaszlehetőség',
                'is_right' => 1,
            ),
            17 => 
            array (
                'id' => 18,
                'question_id' => 6,
                'content' => 'Harmadik kvíz első kérdés második válaszlehetőség',
                'is_right' => 0,
            ),
        ));
        
        
    }
}
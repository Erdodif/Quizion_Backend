<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('question')->delete();
        
        DB::table('question')->insert(array (
            0 => 
            array (
                'id' => 1,
                'quiz_id' => 1,
                'content' => 'Első kvíz első kérdés',
                'point' => 100,
            ),
            1 => 
            array (
                'id' => 2,
                'quiz_id' => 1,
                'content' => 'Első kvíz második kérdés',
                'point' => 200,
            ),
            2 => 
            array (
                'id' => 3,
                'quiz_id' => 2,
                'content' => 'Második kvíz első kérdés',
                'point' => 100,
            ),
            3 => 
            array (
                'id' => 4,
                'quiz_id' => 2,
                'content' => 'Második kvíz második kérdés',
                'point' => 200,
            ),
            4 => 
            array (
                'id' => 5,
                'quiz_id' => 2,
                'content' => 'Második kvíz harmadik kérdés',
                'point' => 100,
            ),
            5 => 
            array (
                'id' => 6,
                'quiz_id' => 3,
                'content' => 'Harmadik kvíz első kérdés',
                'point' => 100,
            ),
        ));
        
        
    }
}
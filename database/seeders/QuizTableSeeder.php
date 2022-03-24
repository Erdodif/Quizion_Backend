<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('quiz')->delete();
        
        DB::table('quiz')->insert(array (
            0 => 
            array (
                'id' => 1,
                'header' => 'Első kvíz',
                'description' => 'Első kvíz leírás',
                'active' => 1,
                'seconds_per_quiz' => 10,
            ),
            1 => 
            array (
                'id' => 2,
                'header' => 'Második kvíz',
                'description' => 'Második kvíz leírás',
                'active' => 1,
                'seconds_per_quiz' => 10,
            ),
            2 => 
            array (
                'id' => 3,
                'header' => 'Harmadik kvíz header aminek nagyon hosszú lesz a címe azért hogy lehessen látni hogy fog kinézni',
                'description' => 'Harmadik kvíz leírás ami egy nagyon hosszú leírás lesz hogy lehessen látni hogy mi történik amikor ilyen hosszú leírást kiiratunk',
                'active' => 1,
                'seconds_per_quiz' => 10,
            ),
        ));
        
        
    }
}
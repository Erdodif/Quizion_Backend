<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('users')->delete();
        
        DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'averagequizionenjoyer',
                'email' => 'proba@gmail.com',
                'password' => '$argon2i$v=19$m=65536,t=4,p=1$SlR5YmJWV2VZaFdZYmhtQg$ypm+xQiK1Ejt28zA+d8SCNrZYIkWu992MeabGNrIBXc',
                'xp' => 0,
            ),
            1 => 
            array (
                'id' => 3,
                'name' => 'average2',
                'email' => 'average@2',
                'password' => '$argon2i$v=19$m=65536,t=4,p=1$NERiWDJlWDFBTWtFNGFmdA$rqq6ha1yOH59R75MtpVOqRnykAFjNMpLEuM25LnPO1c',
                'xp' => 0,
            ),
            2 => 
            array (
                'id' => 4,
                'name' => 'erdodif',
                'email' => 'erdodif@gmail.com',
                'password' => '$argon2i$v=19$m=65536,t=4,p=1$a09OZzhqbmtnTk5GU0p1Qw$OhSCpp9ncYU6DZ3FXTrkQi1hrhL6aEg9p57t6Fc+/lg',
                'xp' => 0,
            ),
        ));
        
        
    }
}
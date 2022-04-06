<?php

namespace Database\Seeders;

use Carbon\Carbon;
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
                'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'password' => '$argon2i$v=19$m=65536,t=4,p=1$SlR5YmJWV2VZaFdZYmhtQg$ypm+xQiK1Ejt28zA+d8SCNrZYIkWu992MeabGNrIBXc',
                'remember_token' => 'a&caf78cba748485d3e2cf210ff7605b4368e2279349cc0e8ce2b4a4147269c9',
                'xp' => 0,
            ),
            1 =>
            array (
                'id' => 3,
                'name' => 'average2',
                'email' => 'average@2',
                'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'password' => '$argon2i$v=19$m=65536,t=4,p=1$NERiWDJlWDFBTWtFNGFmdA$rqq6ha1yOH59R75MtpVOqRnykAFjNMpLEuM25LnPO1c',
                'remember_token' => 'a&19672b99d600bca2d2e3fd4c8804b66b8e4a22ad3b8f65d68225e107c03a40',
                'xp' => 0,
            ),
            2 =>
            array (
                'id' => 4,
                'name' => 'erdodif',
                'email' => 'erdodif@gmail.com',
                'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'password' => '$argon2i$v=19$m=65536,t=4,p=1$a09OZzhqbmtnTk5GU0p1Qw$OhSCpp9ncYU6DZ3FXTrkQi1hrhL6aEg9p57t6Fc+/lg',
                'remember_token' => 'e&260d4edc3746eab9bcbe7f506d37975b11adb7083a719f4807e33ee4a5035a',
                'xp' => 0,
            ), 
            3 =>
            array (
                'id' => 5,
                'name' => 'somass',
                'email' => 'somasolti@gmail.com',
                'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'password' => '$argon2i$v=19$m=65536,t=4,p=1$STdGeTRVdE95V0l4Lmp4Zg$Nfcm72u3Zq4bwV6bMvAwzMn9hikyXvQfXzMfMhQxFDQ',
                'remember_token' => 'e&260d4edc3746eab9bcbe7f506d37975b11adb7083a719f4807e33ee4a5035b',
                'xp' => 0,
            ),
            4 =>
            array (
                'id' => 6,
                'name' => 'test',
                'email' => 'test@test.com',
                'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'password' => '$argon2i$v=19$m=16,t=2,p=1$MTIzNDU2Nzg$f0d2gwsV1GILlR7zQjSplw', //test
                'remember_token' => 'e&264d1dac3746eab9acbe7f506d389752b26adb7193a719f4807e33ee4a5035b',
                'xp' => 0,
            ),
        ));
    }
}

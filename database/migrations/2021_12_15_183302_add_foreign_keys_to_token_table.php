<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('token', function (Blueprint $table) {
            $table->foreign(['user_id'], 'token_ibfk_1')->references(['id'])->on('user')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('token', function (Blueprint $table) {
            $table->dropForeign('token_ibfk_1');
        });
    }
}

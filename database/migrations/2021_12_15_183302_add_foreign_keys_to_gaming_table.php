<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToGamingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gaming', function (Blueprint $table) {
            $table->unique(['user_id','quiz_id']);
            $table->foreign(['quiz_id'], 'gaming_ibfk_1')->references(['id'])->on('quiz')->onDelete('CASCADE');
            $table->foreign(['user_id'], 'gaming_ibfk_2')->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gaming', function (Blueprint $table) {
            $table->dropForeign('gaming_ibfk_1');
            $table->dropForeign('gaming_ibfk_2');
        });
    }
}

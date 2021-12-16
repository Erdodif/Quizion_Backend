<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('answer', function (Blueprint $table) {
            $table->foreign(['question_id'], 'answer_ibfk_1')->references(['id'])->on('question');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('answer', function (Blueprint $table) {
            $table->dropForeign('answer_ibfk_1');
        });
    }
}

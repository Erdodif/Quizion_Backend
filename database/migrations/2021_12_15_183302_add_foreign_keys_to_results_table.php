<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('results', function (Blueprint $table) {
            $table->foreign(['user_id'], 'results_ibfk_1')->references(['id'])->on('users');
            $table->foreign(['quiz_id'], 'results_ibfk_2')->references(['id'])->on('quiz');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropForeign('results_ibfk_1');
            $table->dropForeign('results_ibfk_2');
        });
    }
}

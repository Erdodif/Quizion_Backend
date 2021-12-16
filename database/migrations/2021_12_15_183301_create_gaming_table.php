<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gaming', function (Blueprint $table) {
            $table->integer('user_id')->index('user_id');
            $table->integer('quiz_id');
            $table->integer('current')->nullable();
            $table->double('right')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->boolean('question_started')->nullable()->default(false);

            $table->unique(['quiz_id', 'user_id'], 'quiz_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gaming');
    }
}

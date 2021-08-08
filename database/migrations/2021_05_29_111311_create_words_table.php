<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('words', function (Blueprint $table) {
            $table->increments('id_word');
            $table->string('word');
            $table->string('word_v');
            $table->string('phonetics_uk');
            $table->string('phonetics_us');
            $table->string('audio_uk');
            $table->string('audio_us');
            $table->string('dictionary');
            $table->integer('word_frequency');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('words');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookmarkWordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookmark_word', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user');
            $table->integer('id_word');
            $table->string('word_en')->nullable();
            $table->string('note')->nullable()->nullable();
            $table->integer('true')->nullable();
            $table->integer('false')->nullable()->nullable()->nullable()->nullable();
            $table->integer('remember')->nullable()->nullable()->nullable();
            $table->integer('id_bookmark_topic');
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
        Schema::dropIfExists('bookmark_word');
    }
}

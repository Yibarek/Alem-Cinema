<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->integer('length');
            $table->string('catagory');
            $table->string('producers');
            $table->string('directors');
            $table->string('actors')->nullable();
            $table->string('released_year')->nullable();
            $table->string('show_date')->nullable();
            $table->string('show_time')->nullable();
            $table->integer('no_of_shows')->nullable();
            $table->string('description')->nullable();
            $table->string('trailer')->nullable();
            $table->string('poster')->nullable();
            $table->integer('vip')->nullable();
            $table->integer('normal')->nullable();
            $table->integer('views')->nullable();
            $table->integer('likes')->nullable();
            $table->integer('tickets')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
    }
}

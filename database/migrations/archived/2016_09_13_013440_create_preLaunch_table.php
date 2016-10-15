<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreLaunchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_launches', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('position', array('about', 'work', 'mobile', 'launch', 'social', 'email'));
            $table->text('text')->nullable();
            $table->text('link')->nullable();
            $table->integer('mobile')->nullable();
            $table->date('launch')->nullable();
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
        //
    }
}

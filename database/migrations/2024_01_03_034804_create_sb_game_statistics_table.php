<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSbGameStatisticsTable extends Migration
{
    public function up()
    {
        Schema::create('sb_game_statistics', function (Blueprint $table) {
            $table->id();
            // $table->string('hash')->nullable();
            $table->unsignedBigInteger('user1_id')->nullable();
            $table->unsignedBigInteger('user2_id')->nullable();
            $table->json('ships1');
            $table->json('ships2');
            $table->unsignedBigInteger('players_move')->nullable();
            $table->unsignedBigInteger('winner')->nullable();
            $table->unsignedTinyInteger('score')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sb_game_statistics');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeaBattlesTable extends Migration
{
    public function up()
    {
        Schema::create('sea_battles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('score')->default(0);
            $table->unsignedBigInteger('num_of_games')->default(0);
            $table->unsignedBigInteger('num_of_wins')->default(0);
            $table->softDeletes();

            $table->index('user_id', 'sea_battle_user_idx');
            $table->foreign('user_id', 'sea_battle_user_fk')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sea_battles');
    }
}

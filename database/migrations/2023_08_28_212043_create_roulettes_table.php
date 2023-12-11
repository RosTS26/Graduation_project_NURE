<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoulettesTable extends Migration
{
    public function up()
    {
        Schema::create('roulettes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('deposit')->default(1000);
            $table->unsignedBigInteger('num_of_games')->default(0);
            $table->softDeletes();

            $table->index('user_id', 'roulette_user_idx');
            $table->foreign('user_id', 'roulette_user_fk')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('roulettes');
    }
}

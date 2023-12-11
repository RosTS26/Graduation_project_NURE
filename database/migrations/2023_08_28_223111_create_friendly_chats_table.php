<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFriendlyChatsTable extends Migration
{
    public function up()
    {
        Schema::create('friendly_chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('friend_id')->nullable();
            $table->json('chat');
            $table->json('new_msgs');
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id', 'friendly_chat_user_idx');
            $table->foreign('user_id', 'friendly_chat_user_fk')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('friendly_chats');
    }
}

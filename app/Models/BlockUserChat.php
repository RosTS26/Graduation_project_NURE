<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockUserChat extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'block_user_chats';
    protected $guarded = ['id'];
}

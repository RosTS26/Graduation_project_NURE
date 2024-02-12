<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SbGameStatistics extends Model
{
    use HasFactory;

    protected $table = 'sb_game_statistics';
    protected $guarded = ['id'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeaBattle extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;

    protected $table = 'sea_battles';
    protected $guarded = ['id'];

    public function User() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Snake extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false; // Отключение метод создания и обновления

    protected $table = 'snakes';
    protected $guarded = ['id'];

    public function User() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

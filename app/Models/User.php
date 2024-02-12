<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'online',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            // Создание связанных объектов в таблицах для игр
            $user->snake()->create();
            $user->tetris()->create();
            $user->roulette()->create();
            $user->seabattle()->create();
            
            // Создание таблицы друзей
            $user->friend()->create([
                'friends' => json_encode([]),
                'sent_app' => json_encode([]),
                'incoming_app' => json_encode([]),
            ]);
        });
    }

    // Связь один к одному
    public function Snake() {
        return $this->hasOne(Snake::class, 'user_id', 'id');
    }

    public function Tetris() {
        return $this->hasOne(Tetris::class, 'user_id', 'id');
    }

    public function Roulette() {
        return $this->hasOne(Roulette::class, 'user_id', 'id');
    }
    
    public function SeaBattle() {
        return $this->hasOne(SeaBattle::class, 'user_id', 'id');
    }

    public function Friend() {
        return $this->hasOne(Friend::class, 'user_id', 'id');
    }

    // Связь один ко многим
    public function FriendlyChat() {
        return $this->hasMany(FriendlyChat::class, 'user_id', 'id');
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}

<?php

namespace App;

use App\Room;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function rooms()
    {
        return $this->belongsToMany(Room::class,'room_user','user_id', 'room_id')->withPivot('user_id', 'room_id', 'is_baned');
    }

    public function ownRooms()
    {
        return $this->hasMany(Room::class, 'primary_user', 'id');
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\LoadStreams;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LoadStreams;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'twitch_id' , 'access_token', 'refresh_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'access_token', 'refresh_token', 'email'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    public function getProfile() {
        // TODO it is importan to filter out what we are returning and formating it if necessary
        return $this;
    }

    public function getAccessToken() {
        return $this->access_token ?? "";
    }

    public static function getFollowing($limit=100, $fetchAll=true) {
        return self::loadStreams(true,$limit,[],"", $fetchAll);
    }
}

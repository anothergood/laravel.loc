<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'username', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function users()
    {
      return $this->belongsToMany(User::class,'user_user','user_initiator_id','user_id')->withPivot('status')->withTimestamps();
    }

    public function likes()
    {
      return $this->belongsToMany(Post::class,'user_post','user_id','post_id');
    }
}

<?php

namespace App;

use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Orderable;

    protected $fillable = ['title'];

    public function user()
    {
      return $this->belongsTo('App\User');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function likes()
    {
        return $this->hasMany('App\Like');
    }

    public function attachments()
    {
        return $this->hasMany('App\Attachment');
    }
}

<?php

namespace App;

use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Orderable;

    protected $fillable = [
        'title', 'body',
    ];

    public function users()
    {
      return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(User::class, 'user_post','post_id','user_id');
    }

    public function attachments()
    {
         return $this->morphMany(Attachment::class, 'attachable');
    }
}

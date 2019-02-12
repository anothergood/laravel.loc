<?php

namespace App;

use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    use Orderable;

    protected $fillable = ['user_initiator_id','user_id'];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}

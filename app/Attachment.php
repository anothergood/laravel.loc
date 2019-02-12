<?php

namespace App;

use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
  use Orderable;

        protected $fillable = [
          'file_path','file_type',
        ];

        public function posts()
        {
            return $this->belongsTo('App\Post');
        }
}

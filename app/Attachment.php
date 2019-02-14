<?php

namespace App;

use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
          'file_path','file_type',
        ];

    public function attachable()
    {
        return $this->morphTo();
    }
}

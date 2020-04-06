<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mediable extends Model
{
    protected $table = 'mediable';

    protected $fillable = ['media_id', 'mediable_id', 'mediable_type'];

    public $timestamps = false;

//    public function mediable()
//    {
//        $this->morphTo();
//    }
}

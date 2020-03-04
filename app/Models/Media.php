<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = ['filename', 'size', 'mime', 'type', 'uploaded_at'];

    public $timestamps = false;

}

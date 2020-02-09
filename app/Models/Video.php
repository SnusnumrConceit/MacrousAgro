<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    const MEDIA_PATH = '/videos';
    const TMP_MEDIA_PATH = '/tmp' . self::MEDIA_PATH;

    protected $fillable = ['title', 'path'];
}

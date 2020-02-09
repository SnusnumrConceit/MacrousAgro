<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    const MEDIA_PATH = '/photos';
    const TMP_MEDIA_PATH = '/tmp' . self::MEDIA_PATH;

    protected $fillable = ['title', 'path'];
}

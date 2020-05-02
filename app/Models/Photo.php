<?php

namespace App\Models;

use \App\Traits\Mediable;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use Mediable;

    const MEDIA_PATH = '/photos';
    const MAX_FILE_SIZE = 3000;
    const DIMENSIONS = [
        'min_width'  => 580,
        'min_height' => 400,
        'max_width'  => 1280,
        'max_height' => 1024
    ];

    protected $perPage = 15;

    protected $fillable = ['title'];
}

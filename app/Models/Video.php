<?php

namespace App\Models;

use App\Traits\Mediable;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use Mediable;

    const MEDIA_PATH = '/videos';
    const MAX_FILE_SIZE = 6000;
    const DIMENSIONS = [
        'min_width'  => 1280,
        'min_height' => 720,
        'max_width'  => 1920,
        'max_height' => 1280
    ];

    protected $perPage = 15;

    protected $fillable = ['title'];
}

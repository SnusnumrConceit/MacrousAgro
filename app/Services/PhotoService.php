<?php


namespace App\Services;


use App\Http\Requests\Photo\PhotoStoreRequest;
use App\Http\Requests\Photo\PhotoUpdateRequest;
use App\Http\Resources\Photo\PhotoCollection;
use App\Models\Photo;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoService
{
    private $media;

    public function __construct(MediaService $media)
    {
        $this->media = $media;
    }


}

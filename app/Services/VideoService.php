<?php
declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\Video\VideoUpdateRequest;
use App\Http\Requests\Video\VideoUploadRequest;
use App\Http\Requests\Video\VideoStoreRequest;
use App\Http\Resources\Video\VideoCollection;
use App\Models\Video;
use \App\Http\Resources\Video\Video as VideoResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VideoService
{
    private $media;

    public function __construct(MediaService $media)
    {
        $this->media = $media;
    }



    public function search(Request $request)
    {
        $video = Video::query();

        $video->when(isset($request->keyword), function ($q) use ($request) {
           return $q->where('name', 'LIKE', '%' . $request->keyword . '%');
        });

        $videos = $video->paginate(15);

        return response()->json([
            'videos' => new VideoCollection($videos)
        ], 200);
    }

    public function upload(VideoUploadRequest $request)
    {

    }
}

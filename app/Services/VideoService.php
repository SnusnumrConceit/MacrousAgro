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

    public function index(Request $request) : JsonResponse
    {
        try {
            $video = Video::query();

            $video->when(isset($request->keyword), function ($q) use ($request) {
                return $q->where('title', 'LIKE', '%' . $request->keyword . '%');
            });

            $videos = $video->paginate(15);

            return response()->json([
                'videos' => new VideoCollection($videos)
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() : Array
    {
        return [];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VideoStoreRequest $request)
    {
        $data = $request->validated();

        $fileName = substr($request->path, strrpos($request->path, '/'));
        $destination = Video::MEDIA_PATH . $fileName;

        $this->media->move($request->path, $destination);

        $video = Video::create([
            'title' => $request->title,
            'path' => $fileName
        ]);

        if (! $video) {
            throw new \Exception('Видео не было сохранено');
        }

        return response()->json([
                'status' => 'success',
                'msg' => __('video_msg_success_create')
                ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video) : JsonResponse
    {
        return response()->json([
            'video' => $video
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function edit(Video $video)
    {
        return response()->json([
            'video' => new VideoResource($video)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(VideoUpdateRequest $request, Video $video)
    {
        $data = $request->validated();

        $video->update($data);

        return response()->json([
            'status' => 'success',
            'msg' => __('video_msg_success_update')
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        $this->media->delete(Video::MEDIA_PATH . '/' . $video->path);
        $video->delete();

        return response()->json([
            'status' => 'success',
            'msg' => __('video_msg_success_delete')
        ], 200);
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

<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Video;
use App\Traits\Mediable;
use App\Services\VideoService;
use App\Services\MediaService;
use App\Repositories\VideoRepo;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Video\Video as VideoResource;
use App\Http\Resources\Video\VideoCollection;
use Illuminate\Http\Request;
use App\Http\Requests\Video\VideoStoreRequest;
use App\Http\Requests\Video\VideoUpdateRequest;

class VideoController extends Controller
{
    private $video, $media;

    public function __construct(VideoRepo $video, MediaService $media)
    {
        $this->video = $video;
        $this->media = $media;
//        $this->authorizeResource(Video::class, 'video');
    }

    /**
     * Display a listing of the videos.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $videos = $this->video->index($request);

        return response()->json([
            'videos' => new VideoCollection($videos)
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->video->create();
    }

    /**
     * Store a newly created video in storage.
     *
     * @param VideoStoreRequest $request
     * @return JsonResponse
     */
    public function store(VideoStoreRequest $request)
    {
        $media = Mediable::upload(Video::MEDIA_PATH, $request->video, 'videos');

        $video = $this->video->store($request->validated());

        $video->medias()->sync($media->id);

        return response()->json([
            'status' => 'success',
            'msg' => __('videos.response.messages.created')
        ], 200);
    }

    /**
     * Display the video.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {
        return response()->json([
            'video' => new VideoResource($video)
        ], 200);
    }

    /**
     * Show the form for editing the video.
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
     * Update the video in storage.
     *
     * @param VideoUpdateRequest $request
     * @param Video $video
     * @return JsonResponse
     */
    public function update(VideoUpdateRequest $request, Video $video)
    {
        $this->video->update($request->validated(), $video);

        return response()->json([
            'status' => 'success',
            'msg' => __('videos.response.messages.updated')
        ], 200);
    }

    /**
     * Remove the video from storage.
     *
     * @param Video $video
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Video $video)
    {
        $this->video->destroy($video);

        $video->remove($video->medias()->first());

        return response()->json([
            'status' => 'success',
            'msg' => __('videos.response.messages.deleted')
        ], 200);
    }

    /**
     * Получение случайного списка видеороликов для лэндинга
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function random(Request $request)
    {
        $videos = Video::inRandomOrder()->paginate();

        return response()->json([
            'videos' => new VideoCollection($videos)
        ], 200);
    }
}

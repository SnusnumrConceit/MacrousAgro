<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Video\VideoUpdateRequest;
use App\Http\Requests\Video\VideoStoreRequest;
use App\Http\Resources\Video\VideoCollection;
use App\Models\Video;
use App\Repositories\VideoRepo;
use App\Services\MediaService;
use App\Services\VideoService;
use App\Traits\Mediable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\Video\Video as VideoResource;

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
            'msg' => __('video_msg_success_create')
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
            'msg' => __('video_msg_success_update')
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
            'msg' => __('video_msg_success_delete')
        ], 200);
    }
}

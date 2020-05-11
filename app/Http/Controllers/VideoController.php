<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Video\Video as VideoResource;
use App\Http\Resources\Video\VideoCollection;

use Illuminate\Http\Request;
use App\Http\Requests\Video\VideoStoreRequest;
use App\Http\Requests\Video\VideoUpdateRequest;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Video::class, 'video');
    }

    /**
     * Список видео
     *
     * Display a listing of the videos.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $video = Video::query();

        $video->when($request->keyword, function ($q, $keyword) {
            return $q->where('title', 'LIKE', '%' . $keyword . '%');
        });

        $video->when($request->created_at, function ($q, $created_at) {
            return $q->whereBetween('created_at', [$created_at . ' 00:00:00', $created_at . ' 23:59:59']);
        });

        $videos = $video->latest('created_at')->paginate();

        return response()->json([
            'videos' => new VideoCollection($videos)
        ], 200);
    }

    /**
     * Форма создания видео
     *
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->video->create();
    }

    /**
     * Сохранение видео
     *
     * Store a newly created video in storage.
     *
     * @param VideoStoreRequest $request
     * @return JsonResponse
     */
    public function store(VideoStoreRequest $request)
    {
        $video = Video::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => __('videos.response.messages.created')
        ], 200);
    }

    /**
     * Информация о видео
     *
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
     * Форма редактирования видео
     *
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
     * Обновление видео
     *
     * Update the video in storage.
     *
     * @param VideoUpdateRequest $request
     * @param Video $video
     * @return JsonResponse
     */
    public function update(VideoUpdateRequest $request, Video $video)
    {
        $video->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => __('videos.response.messages.updated')
        ], 200);
    }

    /**
     * Удаление видео
     *
     * Remove the video from storage.
     *
     * @param Video $video
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Video $video)
    {
        $video->delete();

        return response()->json([
            'status' => 'success',
            'message' => __('videos.response.messages.deleted')
        ], 200);
    }
}

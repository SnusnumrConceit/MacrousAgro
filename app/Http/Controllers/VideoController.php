<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Video\VideoUpdateRequest;
use App\Http\Requests\Video\VideoUploadRequest;
use App\Http\Requests\Video\VideoStoreRequest;
use App\Models\Video;
use App\Services\MediaService;
use App\Services\VideoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    private $video, $media;

    public function __construct(VideoService $video, MediaService $media)
    {
        $this->video = $video;
        $this->media = $media;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) : ?JsonResponse
    {
        return $this->video->index($request);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VideoStoreRequest $request)
    {
        return $this->video->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {
        return $this->video->show($video);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function edit(Video $video)
    {
        return $this->video->edit($video);
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
        return $this->video->update($request, $video);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        return $this->video->destroy($video);
    }

    public function upload(VideoUploadRequest $request)
    {
        $video = $request->file('file');

        $tmp_path = $this->media->upload(Video::TMP_MEDIA_PATH, $video);

        return response()->json([
            'tmp_path' => $tmp_path
        ], 200);
    }

    public function removeContent(Request $request)
    {
        $this->media->delete($request->path);

        return response()->json([
            'status' => 'success',
            'msg' => 'Видео успешно удалено из временного хранилища'
        ], 200);
    }
}

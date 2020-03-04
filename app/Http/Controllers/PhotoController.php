<?php

namespace App\Http\Controllers;

use App\Http\Requests\Photo\PhotoStoreRequest;
use App\Http\Requests\Photo\PhotoUpdateRequest;
use App\Http\Requests\Photo\PhotoUploadRequest;
use App\Models\Photo;
use App\Services\MediaService;
use App\Services\PhotoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    private $photo, $media;

    public function __construct(PhotoService $photo, MediaService $media)
    {
        $this->photo = $photo;
        $this->media = $media;
//        $this->authorizeResource(Photo::class, 'photo');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) : JsonResponse
    {
        return $this->photo->index($request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PhotoStoreRequest $request)
    {
        return $this->photo->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function show(Photo $photo)
    {
        return $this->photo->show($photo);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function edit(Photo $photo)
    {
        return $this->photo->edit($photo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function update(PhotoUpdateRequest $request, Photo $photo)
    {
        return $this->photo->update($request, $photo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Photo $photo)
    {
        return $this->photo->destroy($photo);
    }

    public function upload(PhotoUploadRequest $request)
    {
        $photo = $request->file('file');

        $tmp_path = $this->media->upload(Photo::TMP_MEDIA_PATH, $photo);

        return response()->json([
            'tmp_path' => $tmp_path
        ], 200);
    }

    public function removeContent(Request $request)
    {
        $this->media->delete($request->path);

        return response()->json([
            'status' => 'success',
            'msg' => 'Фотография успешно удалена из временного хранилища'
        ], 200);
    }
}

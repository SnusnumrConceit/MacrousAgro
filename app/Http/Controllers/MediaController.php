<?php

namespace App\Http\Controllers;

use App\Http\Requests\Media\MediaDestroyRequest;
use App\Http\Requests\Media\MediaStoreRequest;
use App\Models\Media;
use App\Traits\Mediable;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    private $model_path = 'App\Models\\';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(MediaStoreRequest $request)
    {
        $photo = $request->file;

        /** получать класс модели */

        return Mediable::upload(($this->model_path . ucfirst($request->category))::TMP_MEDIA_PATH, $photo, $request->category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function show(Media $media)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function edit(Media $media)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Media $media)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function destroy(Media $media, MediaDestroyRequest $request)
    {
        $model = $this->model_path . ucfirst($request->category);
        (new $model)->remove($media, $request->path);

        return response()->json([
            'message' => "Медиа-объект $media->filename успешно удалён"
        ], 200);
    }
}

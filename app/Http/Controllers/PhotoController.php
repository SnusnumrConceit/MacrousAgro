<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Photo\PhotoStoreRequest;
use App\Http\Requests\Photo\PhotoUpdateRequest;
use App\Http\Resources\Photo\PhotoCollection;
use App\Http\Resources\Photo\Photo as PhotoResource;

class PhotoController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Photo::class, 'photo');
    }

    /**
     * Список фотографий
     *
     * Display a listing of the photos.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $photos = Photo::query();
        
        $photos->when($request->keyword, function ($q, $keyword) {
            return $q->where('title', 'LIKE', '%' . $keyword . '%');
        });

        $photos->when($request->created_at, function ($q, $created_at) {
            return $q->whereBetween('created_at', [$created_at . ' 00:00:00', $created_at . ' 23:59:59']);
        });

        $photos = $photos->latest('created_at')->paginate();

        return response()->json([
            'photos' => new PhotoCollection($photos)
        ], 200);
    }

    /**
     * СОздание фотографии
     *
     * Store a newly created photo in storage.
     *
     * @param PhotoStoreRequest $request
     * @return JsonResponse
     */
    public function store(PhotoStoreRequest $request)
    {
        $photo = Photo::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => __('photos.response.messages.created')
        ], 200);
    }

    /**
     * Информация о фотографии
     *
     * Display the photo.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function show(Photo $photo)
    {
        return response()->json([
            'photo' => new PhotoResource($photo)
        ], 200);
    }

    /**
     * Форма редактирование фотографии
     *
     * Show the form for editing the photo.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function edit(Photo $photo)
    {
        return response()->json([
            'photo' => new PhotoResource($photo)
        ], 200);
    }

    /**
     * Обновление фотографии
     *
     * Update the photo in storage.
     *
     * @param  PhotoUpdateRequest  $request
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function update(PhotoUpdateRequest $request, Photo $photo)
    {
        $photo->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => __('photos.response.messages.updated')
        ], 200);
    }

    /**
     * Удаление фотографии
     *
     * @param Photo $photo
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Photo $photo)
    {
        $photo->delete();

        return response()->json([
            'status' => 'success',
            'message' => __('photos.response.messages.deleted')
        ], 200);
    }
}

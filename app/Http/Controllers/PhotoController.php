<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Traits\Mediable;
use App\Repositories\PhotoRepo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Photo\PhotoStoreRequest;
use App\Http\Requests\Photo\PhotoUpdateRequest;
use App\Http\Resources\Photo\PhotoCollection;
use App\Http\Resources\Photo\Photo as PhotoResource;

class PhotoController extends Controller
{
    private $photo, $media;

    public function __construct(PhotoRepo $photo)
    {
        $this->photo = $photo;
//        $this->authorizeResource(Photo::class, 'photo');
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
        $photos = $this->photo->index($request);

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
        $media = Mediable::upload(Photo::MEDIA_PATH, $request->image, 'photos');

        $photo = $this->photo->store($request->validated());

        $photo->medias()->sync($media->id);

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
        $this->photo->update($request->validated(), $photo);

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
        $this->photo->destroy($photo);

        $photo->remove($photo->medias()->first());

        return response()->json([
            'status' => 'success',
            'message' => __('photos.response.messages.deleted')
        ], 200);
    }

    /**
     * Получение случайных фотографий для лэндинга
     *
     * Display a random listing of the photos for landing page.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function random(Request $request)
    {
        $photos = Photo::inRandomOrder()->paginate();

        return response()->json([
            'photos' => new PhotoCollection($photos)
        ], 200);
    }
}

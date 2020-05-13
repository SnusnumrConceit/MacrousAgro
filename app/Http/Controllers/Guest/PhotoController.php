<?php

namespace App\Http\Controllers\Guest;

use App\Models\Photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Photo\PhotoCollection;

class PhotoController extends Controller
{
    /**
     * Список фотографий
     *
     * Display a listing of the photos.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $photos = Photo::paginate();

        return response()->json([
            'photos' => new PhotoCollection($photos)
        ], 200);
    }

    /**
     * Получение случайных фотографий для лэндинга
     *
     * Display a random listing of the photos for landing page.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function random(Request $request)
    {
        $photos = Photo::inRandomOrder()->paginate();

        return response()->json([
            'photos' => new PhotoCollection($photos)
        ], 200);
    }
}

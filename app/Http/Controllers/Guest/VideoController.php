<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Resources\Video\VideoCollection;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * Список видео
     *
     * Display a listing of the videos.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $videos = Video::paginate();

        return response()->json([
            'videos' => new VideoCollection($videos)
        ], 200);
    }

    /**
     * Получение случайного списка видеороликов для лэндинга
     *
     * Display a random listing of the videos for landing page.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function random(Request $request)
    {
        $videos = Video::inRandomOrder()->paginate();

        return response()->json([
            'videos' => new VideoCollection($videos)
        ], 200);
    }
}

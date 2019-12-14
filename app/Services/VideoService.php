<?php
declare(strict_types=1);

namespace App\Services;


use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VideoService
{
    public function store(Request $request) : JsonResponse
    {
        try {
            $videos = Video::paginate(15);

            return response()->json([
                'videos' => $videos
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }
}

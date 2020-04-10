<?php

namespace App\Repositories;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoRepo
{
    public function index(Request $request)
    {
        $video = Video::query();

        $video->when($request->keyword, function ($q, $keyword) {
            return $q->where('title', 'LIKE', '%' . $keyword . '%');
        });

        $video->when($request->created_at, function ($q, $created_at) {
            return $q->whereBetween('created_at', [$created_at . ' 00:00:00', $created_at . ' 23:59:59']);
        });

        return $video->latest('created_at')->paginate();
    }

    /**
     * Store a newly created video in storage.
     *
     * @param array $videoData
     * @return mixed
     */
    public function store(array $videoData)
    {
        $video = Video::create($videoData);

        return $video;

    }

    /**
     * Update the video in storage.
     *
     * @param array $videoData
     * @param Video $video
     */
    public function update(array $videoData, Video $video)
    {
        $video->update($videoData);
    }


    /**
     * Remove the video from storage.
     *
     * @param Video $video
     * @throws \Exception
     */
    public function destroy(Video $video)
    {
        $video->delete();
    }
}
<?php

namespace App\Observers;

use App\Models\Video;
use App\Traits\Mediable;

class VideoObserver
{
    /**
     * Handle the video "created" event.
     *
     * @param  \App\Models\Video  $video
     * @return void
     */
    public function created(Video $video)
    {
        $media = Mediable::upload(Video::MEDIA_PATH, request()->file('video'), 'videos');
        $video->medias()->sync($media->id);
    }

    /**
     * Handle the video "deleting" event.
     *
     * @param Video $video
     * @throws \Exception
     */
    public function deleting(Video $video)
    {
        if ($video->medias()->exists()) {
            $video->remove($video->medias()->first());
        }
    }
}

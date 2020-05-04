<?php

namespace App\Observers;

use App\Models\Photo;
use App\Traits\Mediable;

class PhotoObserver
{
    /**
     * Handle the photo "created" event.
     *
     * @param  \App\Models\Photo  $photo
     * @return void
     */
    public function created(Photo $photo)
    {
        $media = Mediable::upload(Photo::MEDIA_PATH, request()->file('image'), 'photos');
        $photo->medias()->sync($media->id);
    }

    /**
     * Handle the photo "deleting" event.
     *
     * @param Photo $photo
     * @throws \Exception
     */
    public function deleting(Photo $photo)
    {
        if ($photo->medias()->exists()) {
            $photo->remove($photo->medias()->first());
        }
    }
}

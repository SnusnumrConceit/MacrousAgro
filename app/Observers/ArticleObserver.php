<?php

namespace App\Observers;

use App\Models\Article;
use App\Traits\Mediable;

class ArticleObserver
{
    /**
     * Handle the article "created" event.
     *
     * @param  \App\Models\Article  $article
     * @return void
     */
    public function created(Article $article)
    {
        $media = Mediable::upload(Article::MEDIA_PATH, request()->file('image'), 'articles');
        $article->medias()->sync($media->id);
    }


//    /** TODO узнать можно ли тригерить событие, без изменения полей */
//     * Handle the article "updated" event.
//     *
//     * @param Article $article
//     * @throws \Exception
//     */
//    public function updated(Article $article)
//    {
//        if (! empty(request()->hasFile('image'))) {
//            if ($article->medias()->count()) {
//                $article->remove($article->medias()->first());
//            }
//
//            $media = Mediable::upload(Article::MEDIA_PATH, request()->file('image'), 'articles');
//
//            $article->medias()->sync($media->id);
//        }
//    }

    /**
     * Handle the article "deleting" event.
     *
     * @param Article $article
     * @throws \Exception
     */
    public function deleting(Article $article)
    {
        if ($article->medias()->first()) {
            $article->remove($article->medias()->first());
        }
    }
}

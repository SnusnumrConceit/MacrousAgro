<?php

namespace App\Observers;

use App\Models\Product;
use App\Traits\Mediable;

class ProductObserver
{
    /**
     * Handle the product "created" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        if (request()->hasFile('image')) {
            $media = Mediable::upload(Product::MEDIA_PATH, request()->file('image'), 'products');

            $product->medias()->sync($media->id);
        }
    }

    //    /** TODO узнать можно ли тригерить событие, без изменения полей */
//    /**
//     * Handle the product "updated" event.
//     *
//     * @param Product $product
//     * @throws \Exception
//     */
//    public function updated(Product $product)
//    {
//        if (request()->hasFile('image')) {
//            if ($product->medias()->exists()) {
//                $product->remove($product->medias()->first());
//            }
//
//            $media = Mediable::upload(Product::MEDIA_PATH, request()->file('image'), 'products');
//
//            $product->medias()->sync($media->id);
//        }
//    }

    /**
     * Handle the product "deleting" event.
     *
     * @param Product $product
     * @throws \Exception
     */
    public function deleting(Product $product)
    {
        if ($product->medias()->exists()) {
            $product->remove($product->medias()->first());
        }
    }
}

<?php

namespace App\Repositories;

use App\Models\Photo;
use Illuminate\Http\Request;

class PhotoRepo
{
    public function index(Request $request)
    {
        $photos = Photo::query();

        $photos->when(isset($request->keyword), function ($q) use ($request) {
            return $q->where('title', 'LIKE', '%' . $request->keyword . '%');
        });

        return $photos->latest('created_at')->paginate();
    }

    /**
     * Store a newly created photo in storage.
     *
     * @param array $photoData
     * @return mixed
     */
    public function store(array $photoData)
    {
        $photo = Photo::create($photoData);

        return $photo;
    }

    public function update(array $photoData, Photo $photo)
    {
        $photo->update($photoData);
    }

    public function destroy(Photo $photo)
    {
        $photo->delete();
    }
}
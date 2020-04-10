<?php

namespace App\Repositories;

use App\Models\Photo;
use Illuminate\Http\Request;

class PhotoRepo
{
    public function index(Request $request)
    {
        $photos = Photo::query();

        $photos->when(isset($request->keyword), function ($q, $keyword) {
            return $q->where('title', 'LIKE', '%' . $keyword . '%');
        });

        $photos->when($request->created_at, function ($q, $created_at) {
            return $q->whereBetween('created_at', [$created_at . ' 00:00:00', $created_at . ' 23:59:59']);
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
<?php


namespace App\Services;


use App\Models\Photo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoService
{
    public function store(Request $request) : JsonResponse
    {
        try {
            $photos = Photo::paginate(15);

            return response()->json([
                'photos' => $photos
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public function validateImage(Request $request) : JsonResponse
    {
        try {
            $photo = $request->file('file');

            $errors = [];
            if (! $photo) {
                throw new \Exception('You did not upload a photo!');
            }

            $extensions = [
                'jpg', 'png', 'gif', 'jpeg'
            ];

            if (! in_array(mb_strtolower($photo->getClientOriginalExtension()), $extensions)) {
                throw new \Exception('Wrong file exception!');
            }

            Storage::put($photo);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }
}

<?php


namespace App\Services;


use App\Http\Requests\Photo\PhotoStoreRequest;
use App\Http\Requests\Photo\PhotoUpdateRequest;
use App\Http\Resources\Photo\PhotoCollection;
use App\Models\Photo;
use App\Http\Resources\Photo\Photo as PhotoResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoService
{
    private $media;

    public function __construct(MediaService $media)
    {
        $this->media = $media;
    }

    public function index(Request $request) : JsonResponse
    {
        try {
            $photos = Photo::query();

            $photos->when(isset($request->keyword), function ($q) use ($request) {
                return $q->where('title', 'LIKE', '%' . $request->keyword . '%');
            });

            $photos = $photos->paginate(6);

            return response()->json([
                'photos' => new PhotoCollection($photos)
            ], 200);

        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public function store(PhotoStoreRequest $request)
    {
        $fileName = substr($request->path, strrpos($request->path, '/'));
        $destination = Photo::MEDIA_PATH . $fileName;

        $this->media->move($request->path, $destination);

        $photo = Photo::create([
            'title' => $request->title,
            'path' => $fileName
        ]);

        return response()->json([
            'status' => 'success',
            'msg' => __('photo_msg_success_create')
        ], 200);
    }

    public function create()
    {

    }

    public function edit(Photo $photo)
    {
        return response()->json([
            'photo' => new PhotoResource($photo)
        ], 200);
    }

    public function update(PhotoUpdateRequest $request, Photo $photo)
    {
        $data = $request->validated();

        $photo->update($data);

        return response()->json([
            'status' => 'success',
            'msg' => __('photo_msg_success_update')
        ], 200);
    }

    public function destroy(Photo $photo)
    {
        $this->media->delete(Photo::MEDIA_PATH . '/' . $photo->path);
        $photo->delete();

        return response()->json([
            'status' => 'success',
            'msg' => __('photo_msg_success_delete')
        ], 200);
    }
}

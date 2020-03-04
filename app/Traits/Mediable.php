<?php

namespace App\Traits;

use App\Models\Media;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

trait Mediable {
    private $media_path = '';
    private $tmp_media_path = '/tmp';
    private $max_file_size = 6000;
    private $max_file_width = 600;
    private $max_file_height = 580;

    public function content()
    {
        return $this->hasOneThrough(Media::class, \App\Models\Mediable::class, 'mediable_id', 'id', 'id', 'media_id');
    }

    public function media()
    {
        return $this->morphOne(\App\Models\Mediable::class, 'mediable');
    }

    public static function upload($tmp_path, $file, $category) : JsonResponse
    {
        if (! Storage::disk('public')->exists($tmp_path)) {
            Storage::disk('public')->makeDirectory($tmp_path);
        }

        $url = Storage::disk('public')->put($tmp_path, $file);

        $media = Media::create([
            'filename' => substr($url, strrpos($url, '/') + 1),
            'mime' => $file->getMimeType(),
            'size' => $file->getClientSize(),
            'type' => $category,
            'uploaded_at' => Carbon::now()
        ]);

        return response()->json(['url' => '/' . $url, 'media_id' => $media->id], 200);
    }

    public static function move(string $from, string $to) : void
    {
        if ( ! Storage::disk('public')->move($from, $to)) {
            throw new \Exception(__('file_storage_move_error'));
        }
    }

    public function remove(Media $media, string $path) : void
    {
        if (! Storage::disk('public')->delete($path)) {
            throw new \Exception(__('file_storage_delete_error'));
        }

        $media->delete();
    }

    public static function modelsUsingTrait($traitName) : array
    {
        return array_filter(
            array_map(function ($filename) {
                if ($filename === '.' || $filename === '..' || is_dir($filename) || ! preg_match('/^.*\.(php)$/i', $filename)) {
                    return false;
                }

                $className = substr($filename, 0, -4);

                return in_array('App\\Traits\\Mediable', class_uses('App\Models\\' . $className)) ? $className : false;
            }, scandir(app_path('Models')))

        );
    }
}
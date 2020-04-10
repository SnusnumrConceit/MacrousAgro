<?php

namespace App\Traits;

use App\Models\Media;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait Mediable {
    private $media_path = '';
    private $tmp_media_path = '/tmp';
    private $max_file_size = 6000;
    private $max_file_width = 600;
    private $max_file_height = 580;

    /**
     * Получить Медиа-объект
     *
     * @return mixed
     */
    public function medias()
    {
        return $this->morphToMany(Media::class, 'mediable');
    }

    /**
     *
     */
    public function getSrcAttribute()
    {
        return self::medias()->exists() ? Storage::url(self::MEDIA_PATH . '/' . self::medias()->first()->filename) : '';
    }

    /**
     * Загрузка файла во временную директорию
     *
     * @param string $destination - временный путь к файлу
     * @param UploadedFile $file - содержимое файла
     * @param string $category - наименование категории
     * @return Media $media
     */
    public static function upload(string $destination, UploadedFile $file, string $category) : Media
    {
        if (! Storage::disk('public')->exists($destination)) {
            Storage::disk('public')->makeDirectory($destination);
        }

        $path = Storage::disk('public')->put($destination, $file);

        $media = Media::create([
            'filename' => substr($path, strrpos($path, '/') + 1),
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
            'type' => $category,
            'uploaded_at' => Carbon::now()
        ]);

        return $media;
    }

    /**
     * Перемещение загруженного файла из временной в конечную директорию
     *
     * @param string $from
     * @param string $to
     * @throws \Exception
     */
    public static function move(string $from, string $to) : void
    {
        if ( ! Storage::disk('public')->move($from, $to)) {
            throw new \Exception(__('file_storage_move_error'));
        }
    }

    /**
     * Удаление медиа-сущности и файла
     *
     * @param Media $media
     * @throws \Exception
     */
    public function remove(Media $media) : void
    {
        if (! Storage::disk('public')->delete(self::MEDIA_PATH . '/'. $media->filename)) {
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
<?php
/**
 * Created by PhpStorm.
 * User: snusnumr
 * Date: 09.02.20
 * Time: 15:36
 */

namespace App\Services;


use App\Exceptions\ApiErrorMessageException;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaService
{
    public function upload(string $path, UploadedFile $file)
    {
        if (! Storage::disk('public')->exists($path)) {
            Storage::disk('public')->makeDirectory($path);
        }

        $url = Storage::disk('public')->put($path, $file);

        return '/' . $url;
    }

    public function move(string $tmp_path, string $destination_path)
    {
        if ( ! Storage::disk('public')->move($tmp_path, $destination_path)) {
            throw new ApiErrorMessageException(__('file_storage_move_error'));
        }
    }

    public function delete(string $path)
    {
        if (! Storage::disk('public')->delete($path)) {
            throw new ApiErrorMessageException(__('file_storage_delete_error'));
        }
    }

    public function assign(UploadedFile $file)
    {
        dd($file);
    }
}
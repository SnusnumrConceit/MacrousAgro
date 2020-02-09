<?php
/**
 * Created by PhpStorm.
 * User: snusnumr
 * Date: 09.02.20
 * Time: 15:36
 */

namespace App\Services;


use Illuminate\Support\Facades\Storage;

class MediaService
{
    public function upload($tmp_path, $file)
    {
        if (! Storage::disk('public')->exists($tmp_path)) {
            Storage::disk('public')->makeDirectory($tmp_path);
        }

        $url = Storage::disk('public')->put($tmp_path, $file);

        return '/' . $url;
    }

    public function move(string $tmp_path, string $destination_path)
    {
        if ( ! Storage::disk('public')->move($tmp_path, $destination_path)) {
            throw new \Exception(__('file_storage_move_error'));
        }
    }

    public function delete(string $path)
    {
        if (! Storage::disk('public')->delete($path)) {
            throw new \Exception(__('file_storage_delete_error'));
        }
    }
}
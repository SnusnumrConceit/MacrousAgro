<?php
declare(strict_types=1);

namespace App\Services;


use Illuminate\Http\Request;

class ProductService
{
    private $media;

    public function __construct(MediaService $media)
    {
        $this->media = $media;
    }
}
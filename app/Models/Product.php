<?php

namespace App\Models;

use App\Traits\Mediable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Mediable;

    const MEDIA_PATH = '/products';
    const MAX_FILE_SIZE = 3000; // in kbytes
    const DIMENSIONS = [
        'min_width'  => 580,
        'min_height' => 400,
        'max_width'  => 1280,
        'max_height' => 1024
    ];

    protected $fillable = ['title', 'description', 'price', 'category_id'];

    protected $appends = ['src', 'creation_date', 'updating_date'];

    protected $dates = ['created_at', 'updated_at'];

    protected $perPage = 15;

    public function getDisplayCreatedAtAttribute()
    {
        return $this->created_at->toDateString() < now()->toDateString()
            ? $this->created_at->format('d.m.Y H:i:s')
            : $this->created_at->diffForHumans();
    }

    public function getDisplayUpdatedAtAttribute()
    {
        return $this->updated_at->toDateString() < now()->toDateString()
            ? $this->updated_at->format('d.m.Y H:i:s')
            : $this->updated_at->diffForHumans();
    }
}

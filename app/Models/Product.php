<?php

namespace App\Models;

use App\Traits\Mediable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = ['title', 'description', 'price', 'category_id'];

    protected $appends = ['url', 'creation_date', 'updating_date'];

    protected $dates = ['created_at', 'updated_at'];

    const MEDIA_PATH = 'products';
    const TMP_MEDIA_PATH = 'tmp/' . self::MEDIA_PATH;
    const MAX_FILE_SIZE = 6000;
//    const MAX_FILE_WIDTH = 520;
//    const MAX_FILE_HEIGHT = 580;

    public function getUrlAttribute()
    {
        return ($this->content !== null ) ? Storage::url(self::MEDIA_PATH . '/' . $this->content->filename) : '';
    }

    public function getCreationDateAttribute()
    {
        return $this->created_at->toDateString() < now()->toDateString()
            ? $this->created_at->format('d.m.Y H:i:s')
            : $this->created_at->diffForHumans();
    }

    public function getUpdatingDateAttribute()
    {
        return $this->updated_at->toDateString() < now()->toDateString()
            ? $this->updated_at->format('d.m.Y H:i:s')
            : $this->updated_at->diffForHumans();
    }

    use Mediable;
}

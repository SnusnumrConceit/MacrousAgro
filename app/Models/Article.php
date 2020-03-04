<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \App\Traits\Mediable;

class Article extends Model
{
    use Mediable;

    protected $fillable = ['title', 'description', 'image', 'publication_date', 'is_publicated'];

    protected $appends = ['formatted_publication_date', 'formatted_created_at', 'formatted_updated_at'];

    protected $dates = ['publication_date', 'created_at', 'updated_at'];

    protected $casts = ['is_publicated' => 'boolean'];

    public function getFormattedPublicationDateAttribute()
    {
        return $this->publication_date->format('d.m.Y');
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('d.m.Y H:i:s');
    }

    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at->format('d.m.Y H:i:s');
    }
}

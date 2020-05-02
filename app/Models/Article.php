<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \App\Traits\Mediable;

class Article extends Model
{
    use Mediable;

    const MEDIA_PATH = '/articles';
    const MAX_FILE_SIZE = 3000; // in kbytes
    const DIMENSIONS = [
        'min_width'  => 580,
        'min_height' => 400,
        'max_width'  => 1280,
        'max_height' => 1024
    ];

    protected $perPage = 10;

    protected $fillable = ['title', 'description', 'publication_date', 'is_publicated'];

    protected $appends = ['display_publication_date', 'display_created_at', 'display_updated_at', 'src'];

    protected $dates = ['publication_date', 'created_at', 'updated_at'];

    protected $casts = [
        'is_publicated' => 'boolean',
        'publication_date' => 'date:Y-m-d'
    ];

    /**
     * Отформатированная дата публикации
     *
     * @return mixed
     */
    public function getDisplayPublicationDateAttribute()
    {
        return $this->publication_date->format('d.m.Y');
    }

    /**
     * Отформатированная дата и время создания статьи
     *
     * @return mixed
     */
    public function getDisplayCreatedAtAttribute()
    {
        return $this->created_at->format('d.m.Y H:i:s');
    }

    /**
     * Отформатированная дата и время изменения статьи
     *
     * @return mixed
     */
    public function getDisplayUpdatedAtAttribute()
    {
        return $this->updated_at->format('d.m.Y H:i:s');
    }

    /**
     * Вывод опубликованных статей
     *
     * @param $query
     * @return mixed
     */
    public function scopePublicated($query)
    {
        return $query->where('is_publicated', '=', 1)
            ->where('publication_date', '<', now()->format('Y-m-d H:i:s'));
    }
}

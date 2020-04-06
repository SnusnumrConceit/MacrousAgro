<?php

namespace App\Http\Resources\Article;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleDetail extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title'                      => $this->title,
            'description'                => $this->description,
            'formatted_publication_date' => $this->formatted_publication_date,
            'formatted_created_at'       => $this->formatted_created_at,
        ];
    }
}

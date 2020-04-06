<?php

namespace App\Http\Resources\Article;

use Illuminate\Http\Resources\Json\JsonResource;

class Article extends JsonResource
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
            'formatted_publication_date' => $this->display_publication_date,
            'formatted_created_at'       => $this->display_created_at,
            'formatted_updated_at'       => $this->display_updated_at,
            'src'                        => $this->src
        ];
    }
}

<?php

namespace App\Http\Requests\Article;

class ArticleUpdateRequest extends ArticleStoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return parent::rules();
    }
}

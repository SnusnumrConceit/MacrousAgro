<?php

namespace App\Http\Requests\Article;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;

class ArticleStoreRequest extends FormRequest
{
    private $minWidth, $minHeight, $maxWidth, $maxHeight, $maxSize;

    protected function prepareForValidation()
    {
        $this->minWidth  = Article::DIMENSIONS['min_width'];
        $this->minHeight = Article::DIMENSIONS['min_height'];
        $this->maxWidth  = Article::DIMENSIONS['max_width'];
        $this->maxHeight = Article::DIMENSIONS['max_height'];
        $this->maxSize   = Article::MAX_FILE_SIZE;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'             =>  'required|string|max:255',
            'description'       =>  'required|string|max:4000',
            'is_publicated'     =>  'required|boolean',
            'publication_date'  =>  'required|date|date_format:Y-m-d',
            'image'             =>  "nullable|file|mimes:jpg,jpeg,png|max:$this->maxSize|dimensions:min_width:$this->minWidth,min_height:$this->minHeight,max_width:$this->maxWidth,max_height:$this->maxHeight"
        ];
    }

    public function attributes()
    {
        return [
            'title' => __('articles.validation.attributes.title'),
            'description' => __('articles.validation.attributes.description'),
            'is_publicated' => __('articles.validation.attributes.is_publicated'),
            'publication_date' => __('articles.validation.attributes.publication_date'),
            'image' => __('articles.validation.attributes.image'),
        ];
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException(__('form_request_authorization_error'), 403);
    }

    protected function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator, response()->json([
            'status' => 'error',
            'message' => __('form_request_validation_failed_error'),
            'errors' => $validator->errors()
        ], 500)));
    }
}

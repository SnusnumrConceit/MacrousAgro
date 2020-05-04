<?php

namespace App\Http\Requests\Photo;

use App\Models\Photo;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class PhotoStoreRequest extends FormRequest
{
    private $minWidth, $minHeight, $maxWidth, $maxHeight, $maxSize;

    protected function prepareForValidation()
    {
        $this->minWidth  = Photo::DIMENSIONS['min_width'];
        $this->minHeight = Photo::DIMENSIONS['min_height'];
        $this->maxWidth  = Photo::DIMENSIONS['max_width'];
        $this->maxHeight = Photo::DIMENSIONS['max_height'];
        $this->maxSize   = Photo::MAX_FILE_SIZE;
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
            'title' => 'nullable|string|max:100',
            'image' => "file|mimes:jpg,jpeg,png|max:$this->maxSize|dimensions:min_width:$this->minWidth,min_height:$this->minHeight,max_width:$this->maxWidth,max_height:$this->maxHeight"
        ];
    }

    public function attributes()
    {
        return [
            'image' => __('photos.validation.attributes.image')
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

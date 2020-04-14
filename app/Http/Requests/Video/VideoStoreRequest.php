<?php

namespace App\Http\Requests\Video;

use App\Models\Video;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class VideoStoreRequest extends FormRequest
{
    private $minWidth, $minHeight, $maxWidth, $maxHeight, $maxSize;

    protected function prepareForValidation()
    {
        $this->minWidth  = Video::DIMENSIONS['min_width'];
        $this->minHeight = Video::DIMENSIONS['min_height'];
        $this->maxWidth  = Video::DIMENSIONS['max_width'];
        $this->maxHeight = Video::DIMENSIONS['max_height'];
        $this->maxSize   = Video::MAX_FILE_SIZE;
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
            'title' => 'required|max:100',
            'video' => 'file|mimes:mpg,mpeg,mp4|max:30000'
        ];
    }

    public function attributes()
    {
        return [
            'video' => __('videos.validation.attributes.video')
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
            'msg' => __('form_request_validation_failed_error'),
            'errors' => $validator->errors()
        ], 500)));
    }
}

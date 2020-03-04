<?php

namespace App\Http\Requests\Product;

use App\Traits\Mediable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductUploadRequest extends FormRequest
{
    private $model, $maxHeight = 6000, $maxWidth = 1920, $maxSize = 1080;
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
            'category' => 'required|mediabled',
            'file' => 'required|mimes:jpg,png,jpeg,size:6000|max_file_width|max_file_height'
        ];
    }

    public function withValidator($validator)
    {
        $validator->addExtension('mediabled', function ($attribute, $value) {
            return $this->validateMediable($value);
        });

        $validator->addReplacer('mediabled', function ($message, $attribute, $rule, $parameters, $validator) {
            return __('validation.non_mediable', ['category' => $attribute]);
        });

        $validator->addExtension('max_file_width', function ($attribute, $value) {
            return getimagesize($value)[0] < $this->maxWidth;
        });

        $validator->addReplacer('max_file_width', function ($message, $attribute, $rule, $parameters, $validator) {
            return __('validation.max_file_width', ['value' => $this->maxWidth ]);
        });

        $validator->addExtension('max_file_height', function ($attribute, $value) {
            return getimagesize($value)[0] < $this->maxHeight;
        });

        $validator->addReplacer('max_file_height', function ($message, $attribute, $rule, $parameters, $validator) {
            return __('validation.max_file_height', ['value' => $this->maxHeight ]);
        });
    }

    private function validateMediable($value)
    {
        $mediatable_models = Mediable::modelsUsingTrait('App\\Traits\\Mediable');

        $needless = ucfirst($value);

        if (! in_array($needless, $mediatable_models)) {
            return false;
        }

        $this->model = 'App\\Models\\' . $needless;
        $this->defineMaxWidth();
        $this->defineMaxHeight();

        return true;
    }

    private function defineMaxWidth() {
        $this->maxWidth = defined($this->model.'::MAX_FILE_WIDTH')
            ?  $this->model::MAX_FILE_WIDTH : $this->maxWidth;
    }

    private function defineMaxHeight() {
        $this->maxHeight = defined($this->model.'::MAX_FILE_HEIGHT')
            ?  $this->model::MAX_FILE_WIDTH : $this->maxHeight;
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
            'error' => $validator->errors()
        ], 500)));
    }
}

<?php

namespace App\Http\Requests\Media;

use App\Traits\Mediable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;

class MediaDestroyRequest extends FormRequest
{
    private $model;
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
            'category' => 'required|mediabled'
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
    }

    private function validateMediable($value)
    {
        $mediatable_models = Mediable::modelsUsingTrait('App\\Traits\\Mediable');

        $needless = ucfirst($value);

        if (! in_array($needless, $mediatable_models)) {
            return false;
        }

        $this->model = 'App\\Models\\' . $needless;

        return true;
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
            'error' => $validator->errors()
        ], 500)));
    }
}

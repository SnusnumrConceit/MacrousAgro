<?php

namespace App\Http\Requests\Order;

use App\Http\Resources\User\User;
use function foo\func;
use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
{
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
            'products'    => 'required|array|min:1',
//            'products.*'  => 'required|string|unique',
//            'customer'    => 'required|exists:users,id|is_customer',
//            'status_code' => 'required|exists:order_status_codes,id'
        ];
    }

    public function attributes()
    {
        return [
            'products'     => __('orders.attributes.products'),
//            'customer'     => __('orders.attributes.customer'),
//            'status_code'  => __('orders.attributes.status_code')
        ];
    }

//    public function withValidator($validator)
//    {
//        $validator->addExtension('is_customer', function ($attribute, $value) {
//           $user = User::find($value);
//           return $user->hasRole('customer');
//        });
//
//        $validator->addReplacer('is_customer', function () {
//           return __('orders.validation.not_is_customer');
//        });
//    }
}

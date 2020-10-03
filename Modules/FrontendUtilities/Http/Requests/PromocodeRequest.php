<?php

namespace Modules\FrontendUtilities\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PromocodeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $delete_check = ',deleted_at,NULL';
        switch ($this->getMethod()) {
            case "GET":
            case "DELETE":
                $rules = [
                    'promocode' => 'required|integer|exists:promocodes,id'.$delete_check,
                ];
                break;
            case "POST":
                $rules = [
                    'code' => 'required|string|unique:promocodes,code',
                    'minimum_price' => 'required|numeric|between:0,9999999999.99',
                    'maximum_price' => 'required|numeric|between:0,9999999999.99|gt:minimum_price',
                    'reward' => 'required|numeric|between:0,9999999999.99',
                    'max_discount_amount' => 'numeric|between:0,9999999999.99',
                    'usage_per_user' => 'required|integer|min:1|max:2147483647',
                    'is_free_shipping' => 'boolean',
                    'discount_type' => 'boolean',
                    'is_active' => 'required|boolean',
                    'users_count' => 'required|integer|min:1|max:2147483647',
                    'from' => 'required|date_format:Y-m-d H:i:s',
                    'to' => 'required|date_format:Y-m-d H:i:s|after:from',

                    'users' => 'array',
                    'users.*' => [
                        'required',
                        'integer',
                        Rule::exists('users', 'id')->where(function ($query) {
                            $query->where('user_type', 2)->where('deleted_at', null);
                        }),
                    ],

                    'products' => 'array',
                    'products.*' => 'required|integer|exists:products,id'.$delete_check,

                    'categories' => 'array',
                    'categories.*' => 'required|integer|exists:categories,id'.$delete_check,

                    'brands' => 'array',
                    'brands.*' => 'required|integer|exists:brands,id'.$delete_check,

                    'country_id' => 'integer|exists:countries,id'.$delete_check,

                ];
                break;
            case "PUT":
            case "PATCH":
                $rules = [
                    'promocode' => 'required|integer|exists:promocodes,id'.$delete_check,

                    'code' => 'string|unique:promocodes,code,'.request('promocode'),
                    'minimum_price' => 'numeric|between:0,9999999999.99',
                    'maximum_price' => 'numeric|between:0,9999999999.99|gt:minimum_price',
                    'reward' => 'numeric|between:0,9999999999.99',
                    'max_discount_amount' => 'numeric|between:0,9999999999.99',
                    'usage_per_user' => 'integer|min:1|max:2147483647',
                    'is_free_shipping' => 'boolean',
                    'discount_type' => 'boolean',
                    'is_active' => 'boolean',
                    'users_count' => 'integer|min:1|max:2147483647',
                    'from' => 'date',
                    'to' => 'date|after:from',

                    'users' => 'array',
                    'users.*' => [
                        'required',
                        'integer',
                        Rule::exists('users', 'id')->where(function ($query) {
                            $query->where('user_type', 2)->where('deleted_at', null);
                        }),
                    ],

                    'products' => 'array',
                    'products.*' => 'required|integer|exists:products,id'.$delete_check,

                    'categories' => 'array',
                    'categories.*' => 'required|integer|exists:categories,id'.$delete_check,

                    'brands' => 'array',
                    'brands.*' => 'required|integer|exists:brands,id'.$delete_check,

                    'country_id' => 'integer|exists:countries,id'.$delete_check,

                ];
                break;
            default:
                $rules = [];
                break;
        }
        return $rules;
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

    protected function prepareForValidation()
    {
        prepareBeforeValidation($this, [], 'promocode');
    }


}

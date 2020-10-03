<?php

namespace Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentMethodsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $delete_check = ',deleted_at,NULL';
        $active_languages = implode(',', getActiveISO());
        switch ($this->getMethod()) {
            case 'GET':
            case 'DELETE':
                $rules = [
                    'payment_method' => 'required|integer|exists:payment_methods,id'.$delete_check
                ];
                break;
            case 'POST':
                $rules = [
                    'data' => 'required|array',
                    'data.*.lang' => 'required|string|distinct|in:'.$active_languages,
                    'data.*.name' => 'required|string|max:255',

                    'key' => 'required|string|unique:payment_methods,key',
                    'is_active' => 'boolean',
                    'country_id' => 'integer|exists:countries,id'.$delete_check,
                ];
                break;
            case 'PUT':
            case 'PATCH':
                $rules = [
                    'payment_method' => 'required|integer|exists:payment_methods,id'.$delete_check,

                    'data' => 'array',
                    'data.*.lang' => 'required|string|distinct|in:'.$active_languages,
                    'data.*.name' => 'required|string|max:255',

                    'key' => 'string|unique:payment_methods,key,'.request('payment_method'),
                    'is_active' => 'boolean',
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

    public function prepareForValidation()
    {
        prepareBeforeValidation($this, [], 'payment_method');
    }
}

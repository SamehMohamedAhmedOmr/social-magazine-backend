<?php

namespace Modules\WareHouse\Http\Requests\CMS\Order;

use Illuminate\Foundation\Http\FormRequest;

class OrderExportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $delete_check = ',deleted_at,NULL';
        return [
            'orders' => 'array',

            'orders.*' => 'required|integer|exists:orders,id'.$delete_check,
        ];
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
}

<?php

namespace Modules\WareHouse\Http\Requests\CMS\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderStatusRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'names' => 'required|array',
            'names.*.name' => 'required|string|max:200',
            'names.*.language' => "required|string|max:4|exists:languages,iso",
            'is_active' => 'required|boolean',
            'key' => 'required|string|alpha_dash|unique:order_statuses,key'
        ];
    }
}

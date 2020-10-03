<?php

namespace Modules\WareHouse\Http\Requests\CMS\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'names' => 'sometimes|required|array',
            'names.*.name' => 'string|max:200',
            'names.*.language' => "required_with:names.*.name|string|max:4|exists:languages,iso",
            'is_active' => 'nullable|boolean',
            'key' => 'sometimes|string|alpha_dash|unique:order_statuses,key,'.$this->status
        ];
    }
}

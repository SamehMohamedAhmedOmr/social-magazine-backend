<?php

namespace Modules\WareHouse\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockRequest extends FormRequest
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
            'product' => 'required|array',
            'file_path' => 'required|string',
            'product.*.product_id' => 'required|integer|distinct|exists:products,id'.$delete_check,
            'product.*.qty' => 'required|integer|max:99999999999999999999',
            'product.*.available' => 'sometimes|boolean',
            'from_warehouse' => 'required_if:type,1|integer|different:to_warehouse|exists:warehouses,id'.$delete_check,
            'to_warehouse' => 'required|integer|exists:warehouses,id'.$delete_check,
            'type' => 'required|boolean', // 0 Added , 1 Moved
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

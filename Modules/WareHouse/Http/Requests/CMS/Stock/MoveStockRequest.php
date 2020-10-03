<?php

namespace Modules\WareHouse\Http\Requests\CMS\Stock;

use Illuminate\Foundation\Http\FormRequest;

class MoveStockRequest extends FormRequest
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
            'product_id' => 'required|integer|exists:products,id'.$delete_check,
            'qty' => 'required|integer|max:99999999999999999999',
            'available' => 'sometimes|boolean',
            'from_warehouse' => 'required|integer|different:to_warehouse|exists:warehouses,id'.$delete_check,
            'to_warehouse' => 'required|integer|exists:warehouses,id'.$delete_check,
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

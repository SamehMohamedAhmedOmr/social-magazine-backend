<?php

namespace Modules\WareHouse\Http\Requests\CMS\Stock;

use Illuminate\Foundation\Http\FormRequest;

class AddStockRequest extends FormRequest
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
            'to_warehouse' => 'required|integer|exists:warehouses,id'.$delete_check,
            'company_id' => 'required|integer|exists:companies,id'.$delete_check,
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

<?php

namespace Modules\WareHouse\Http\Requests\CMS\Stock;

use Illuminate\Foundation\Http\FormRequest;

class SellWithAvailabilityRequest extends FormRequest
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
            'products' => 'required|array',
            'products.*' => 'required|integer|exists:products,id'.$delete_check,
            'available' => 'required|boolean',
            'warehouse_id' => 'required|integer|exists:warehouses,id'.$delete_check,
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

    protected function prepareForValidation()
    {
        prepareBeforeValidation($this, [''], 'product');
    }
}

<?php

namespace Modules\WareHouse\Http\Requests\Shipment;

use Illuminate\Foundation\Http\FormRequest;

class ShipmentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'order_id' => 'required|exists:orders,id|unique:shipments,order_id',
            'company' => 'required|exists:shipping_companies,key',
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

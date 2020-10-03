<?php

namespace Modules\WareHouse\Http\Requests\Shipment;

use Illuminate\Foundation\Http\FormRequest;

class WebHookRequest extends FormRequest
{
    public function rules()
    {
        return [
            '_id' => 'required|string|exists:shipments,tracking_id',
            'state' => 'required|numeric',
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

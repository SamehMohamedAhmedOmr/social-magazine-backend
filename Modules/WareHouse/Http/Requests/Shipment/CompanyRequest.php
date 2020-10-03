<?php

namespace Modules\WareHouse\Http\Requests\Shipment;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    public function rules()
    {
        $sometimes_or_required = $this->method() == 'POST' ? 'required|' : 'sometimes|';
        $unique_or_not = $this->method() == 'POST' ? '' : '|unique:shipping_companies,key,'.$this->company;
        return [
            'name' => $sometimes_or_required.'|string|min:2|max:128',
            'key' => $sometimes_or_required.'|string|min:2|max:128'.$unique_or_not,
            'is_active' => 'sometimes|boolean',
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

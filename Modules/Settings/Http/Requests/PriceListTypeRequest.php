<?php

namespace Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PriceListTypeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->getMethod()) {
            case 'GET':
            case 'DELETE':
                $rules = [
                    'price_list_type' => 'required|integer|exists:price_list_types,id'
                ];
                break;

            case 'POST':
                $rules = [
                    'name' => 'required|string|max:255',
                ];
                break;
            case 'PUT':
            case 'PATCH':
                $rules = [
                    'price_list_type' => 'required|integer|exists:price_list_types,id',
                    'name' => 'string|max:255',
                ];
                break;
            default:
                $rules = [];
                break;
        }

        return  $rules;
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
        if ($this->getMethod() == 'GET' || $this->getMethod() == 'DELETE' || $this->getMethod() == 'PUT') { // Add - Delete - PUT

            // get the query Data
            $query_data = array_map('trim', $this->all());

            $query_data['price_list_type'] = request('price_list_type');

            // replace old input with new input
            $this->replace($query_data);
        }
    }
}

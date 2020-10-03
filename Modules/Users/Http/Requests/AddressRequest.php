<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $delete_check = ',deleted_at,NULL';

        switch ($this->getMethod()) {
            case 'GET':
            case 'DELETE':
            $default = [
                    'address' => 'required|integer|exists:address,id'.$delete_check
                ];
                break;
            case 'POST':
                $default = [
                    // Required Address Info
                    'user_id' => 'integer|exists:users,id'.$delete_check,

                    'country_id' => 'integer|exists:countries,id'.$delete_check,
                    'city_id' => 'integer|exists:districts,id'.$delete_check,
                    'district_id' => 'required|integer|exists:districts,id'.$delete_check,

                    'street' => 'required|string|max:254',
                    'title' => 'string|max:254',
                    'nearest_landmark' => 'string|max:254',
                    // Optional Address Info
                    'address_phone' => 'string|max:254',
                    'building_no' => 'string|max:254',
                    'apartment_no' => 'string|max:254',
                    'floor_no' => 'string|max:254',
                    'lat' => 'string|max:254',
                    'lng' => 'string|max:254',
                ];
                break;
            case 'PUT':
                $default = [
                    'address' => 'required|integer|exists:address,id'.$delete_check,

                    // Required Address Info
                    'country_id' => 'integer|exists:countries,id'.$delete_check,
                    'city_id' => 'integer|exists:districts,id'.$delete_check,
                    'district_id' => 'integer|exists:districts,id'.$delete_check,

                    'street' => 'string|max:254',
                    'title' => 'string|max:254',
                    'nearest_landmark' => 'string|max:254',
                    // Optional Address Info
                    'address_phone' => 'string|max:254',
                    'building_no' => 'string|max:254',
                    'apartment_no' => 'string|max:254',
                    'floor_no' => 'string|max:254',
                    'lat' => 'string|max:254',
                    'lng' => 'string|max:254',
                    'is_active' => 'boolean'
                ];
                break;

            default:
                $default = [];
                break;
        }

        return $default;
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
        prepareBeforeValidation($this, [], 'address');
    }
}

<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Users\Facades\UsersErrorsHelper;

class RegisterRequest extends FormRequest
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
            // Basic Information
            'name' => 'required|string|regex:'.UsersErrorsHelper::regexName().'|max:255',
            'email' => 'required|email:rfc,filter|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|min:4|max:14',

            // Required Address Info

            'country_id' => 'integer|exists:countries,id'.$delete_check,
            'city_id' => 'integer|exists:districts,id'.$delete_check,


            'street' => 'required_with:'.
                                'country_id,'.
                                'city_id,'.
                                'title,'.
                                'district_id,'.
                                'nearest_landmark,'.
                                'building_no,'.
                                'apartment_no,'.
                                'floor_no,'.
                                'lat,'.
                                'lng|string|max:254',

            'title' => 'required_with:'.
                                'country_id,'.
                                'city_id,'.
                                'street,'.
                                'district_id,'.
                                'nearest_landmark,'.
                                'building_no,'.
                                'apartment_no,'.
                                'floor_no,'.
                                'lat,'.
                                'lng|string|max:254',

            'district_id' => 'required_with:'.
                                'country_id,'.
                                'city_id,'.
                                'title,'.
                                'street,'.
                                'nearest_landmark,'.
                                'building_no,'.
                                'apartment_no,'.
                                'floor_no,'.
                                'lat,'.
                                'lng|integer|exists:districts,id'.$delete_check,

            'nearest_landmark' => 'required_with:'.
                                'country_id,'.
                                'city_id,'.
                                'title,'.
                                'district_id,'.
                                'street,'.
                                'building_no,'.
                                'apartment_no,'.
                                'floor_no,'.
                                'lat,'.
                                'lng|string|max:254',

            // Optional Address Info
            'address_phone' => 'nullable|string|max:254',
            'building_no' => 'nullable|string|max:254',
            'apartment_no' => 'nullable|string|max:254',
            'floor_no' => 'nullable|string|max:254',
            'lat' => 'nullable|string|max:254',
            'lng' => 'nullable|string|max:254',
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

<?php

namespace Modules\Catalogue\Http\Requests\CMS;

use Illuminate\Foundation\Http\FormRequest;

class UnitOfMeasureRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $validation = [];
        $delete_check = ',deleted_at,NULL';

        if (request()->getMethod() == 'POST') {
            $validation = [
                'names' => 'required|array',
                'names.*.name' => 'required |string|max:250',
                'names.*.language' => "required|string|max:4|exists:languages,iso",
                'is_active' => 'required|boolean',

                'country_id' => 'integer|exists:countries,id'.$delete_check,

            ];
        } elseif (request()->getMethod() == 'PUT' || request()->getMethod() == 'PATCH') {
            $validation = [
                'names' => 'sometimes|required|array',
                'names.*.name' => 'string|max:250',
                'names.*.language' => "required_with:names.*.name|string|max:4|exists:languages,iso",
                'is_active' => 'nullable|boolean',

                'country_id' => 'integer|exists:countries,id'.$delete_check,

            ];
        }

        return $validation;
    }
}

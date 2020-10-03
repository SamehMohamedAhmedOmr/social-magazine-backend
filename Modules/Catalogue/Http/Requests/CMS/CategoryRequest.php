<?php

namespace Modules\Catalogue\Http\Requests\CMS;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Catalogue\Rules\ParentCategoryCheck;

class CategoryRequest extends FormRequest
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
                'names.*.name' => 'required|string|max:200',
                'names.*.language' => "required|string|max:4|exists:languages,iso",
                'code' => 'required|string|min:1|max:30|unique:categories,code',
                'is_active' => 'required|boolean',
                'parent_id' => 'nullable|integer|exists:categories,id',

                'country_id' => 'integer|exists:countries,id'.$delete_check,

            ];
        } elseif (request()->getMethod() == 'PUT' || request()->getMethod() == 'PATCH') {
            $validation = [
                'names' => 'sometimes|required|array',
                'names.*.name' => 'string|max:200',
                'names.*.language' => "required_with:names.*.name|string|max:4|exists:languages,iso",
                'is_active' => 'nullable|boolean',
                'code' => 'nullable|string|min:1|max:30',
                'parent_id' => ['nullable','integer','exists:categories,id', new ParentCategoryCheck($this)],
                'image' => 'integer|exists:gallery,id',
                'icon' => 'integer|exists:gallery,id',

                'country_id' => 'integer|exists:countries,id'.$delete_check,

            ];
        }

        return $validation;
    }
}

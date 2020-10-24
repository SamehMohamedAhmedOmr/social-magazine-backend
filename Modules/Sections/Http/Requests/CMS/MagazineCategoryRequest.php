<?php

namespace Modules\Sections\Http\Requests\CMS;

use Illuminate\Foundation\Http\FormRequest;

class MagazineCategoryRequest extends FormRequest
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
                    'magazine_category' => 'required|integer|exists:magazine_category,id' . $delete_check,
                ];
                break;
            case 'POST':
                $default = [
                    'content' => 'required|string|max:65535',
                    'is_active' => 'boolean',

                    'images' => 'array',
                    'images.*' => 'required|integer|exists:gallery,id',
                ];
                break;
            case 'PUT':
                $default = [
                    'magazine_category' => 'required|integer|exists:magazine_category,id' . $delete_check,

                    'content' => 'nullable|string|max:65535',
                    'is_active' => 'boolean',

                    'images' => 'array',
                    'images.*' => 'required|integer|exists:gallery,id',
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

    public function attributes()
    {
        return [
            'magazine_category' => 'التصنيف',
            'images' => 'الصور',
            'images.*' => 'الصور',
        ];
    }

    protected function prepareForValidation()
    {
        prepareBeforeValidation($this, [], 'magazine_category');
    }
}

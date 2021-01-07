<?php

namespace Modules\Sections\Http\Requests\CMS;

use Illuminate\Foundation\Http\FormRequest;

class PhotoRequest extends FormRequest
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
                    'photo' => 'required|integer|exists:photos,id' . $delete_check,
                ];
                break;
            case 'POST':
                $default = [
                    'title' => 'required|string|max:255',
                    'content' => 'required|string|max:65535',
                    'is_active' => 'boolean',

                    'images' => 'array',
                    'images.*' => 'required|integer|exists:gallery,id',
                ];
                break;
            case 'PUT':
                $default = [
                    'photo' => 'required|integer|exists:photos,id' . $delete_check,

                    'title' => 'nullable|string|max:255',
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
            'photo' => 'الصورة',
            'title' => 'العنوان',
            'date' => 'التاريخ',
            'images' => 'الصور',
            'images.*' => 'الصور',
        ];
    }

    protected function prepareForValidation()
    {
        prepareBeforeValidation($this, [], 'photo');
    }
}

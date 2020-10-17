<?php

namespace Modules\Sections\Http\Requests\CMS;

use Illuminate\Foundation\Http\FormRequest;

class WhoIsUsRequest extends FormRequest
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
                    'who_is_us_section' => 'required|integer|exists:who_is_us,id' . $delete_check,
                ];
                break;
            case 'POST':
                $default = [
                    'content' => 'required|string|max:100000',
                    'is_active' => 'boolean',
                ];
                break;
            case 'PUT':
                $default = [
                    'who_is_us_section' => 'required|integer|exists:who_is_us,id' . $delete_check,

                    'content' => 'nullable|string|max:100000',
                    'is_active' => 'boolean',
                ];
                break;

            default:
                $default = [];
                break;
        }

        return $default;

    }

    public function attributes()
    {
        return [
            'who_is_us_section' => 'المحتوى'
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


    protected function prepareForValidation()
    {
        prepareBeforeValidation($this, [], 'who_is_us_section');
    }


}

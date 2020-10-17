<?php

namespace Modules\Sections\Http\Requests\CMS;

use Illuminate\Foundation\Http\FormRequest;

class MagazineGoalsRequest extends FormRequest
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
                    'magazine_goal' => 'required|integer|exists:magazine_goals,id' . $delete_check,
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
                    'magazine_goal' => 'required|integer|exists:magazine_goals,id' . $delete_check,

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
            'magazine_goal' => 'المحتوى'
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
        prepareBeforeValidation($this, [], 'magazine_goal');
    }

}

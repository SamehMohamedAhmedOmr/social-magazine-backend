<?php

namespace Modules\Sections\Http\Requests\CMS;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Users\Facades\UsersErrorsHelper;

class AdvisoryBodyRequest extends FormRequest
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
                    'advisory_body' => 'required|integer|exists:advisory_body,id' . $delete_check,
                ];
                break;
            case 'POST':
                $default = [
                    'name' => 'required|string|regex:' . UsersErrorsHelper::regexName() . '|max:255',
                    'job' => 'required|string|max:255',
                    'is_active' => 'boolean',
                ];
                break;
            case 'PUT':
                $default = [
                    'advisory_body' => 'required|integer|exists:advisory_body,id' . $delete_check,

                    'name' => 'nullable|string|regex:' . UsersErrorsHelper::regexName() . '|max:255',
                    'job' => 'nullable|string|max:255',
                    'is_active' => 'boolean',
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
            'advisory_body' => 'الاستشاري'
        ];
    }

    protected function prepareForValidation()
    {
        prepareBeforeValidation($this, [], 'advisory_body');
    }
}

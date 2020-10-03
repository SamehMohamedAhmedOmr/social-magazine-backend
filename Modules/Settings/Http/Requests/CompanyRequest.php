<?php

namespace Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
                $rules = [
                    'company' => 'required|integer|exists:companies,id'.$delete_check,
                ];
                break;
            case 'POST':
                $active_languages = implode(',', getActiveISO());
                $rules = [
                    // company-language table
                    'logo' => 'required|integer|exists:gallery,id',

                    'data' => 'required|array',
                    'data.*.lang' => 'required|string|in:'.$active_languages,
                    'data.*.name' => 'required|string|max:150',
                    'data.*.description' => 'required|string',

                    'country_id' => 'integer|exists:countries,id'.$delete_check,

                ];
                break;
            case 'PUT':
            case "PATCH":
                $active_languages = implode(',', getActiveISO());
                $rules = [
                    'company' => 'required|integer|exists:companies,id'.$delete_check,
                    // company-language table
                    'logo' => 'integer|exists:gallery,id',

                    'data' => 'array',
                    'data.*.lang' => 'required|string|in:'.$active_languages,
                    'data.*.name' => 'required|string|max:150',
                    'data.*.description' => 'required|string',

                    'country_id' => 'integer|exists:countries,id'.$delete_check,

                ];
                break;
            default:
                $rules = [];
                break;
        }
        return $rules;
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

    public function prepareForValidation()
    {
        prepareBeforeValidation($this, ['data'], 'company');
    }
}

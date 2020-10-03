<?php

namespace Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
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
                    'page' => 'required|integer|exists:pages,id'.$delete_check,
                ];
                break;
            case 'POST':
                $active_languages = implode(',', getActiveISO());
                $rules = [
                    'page_url' => 'required|string|max:255',
                    'is_active' => 'boolean',

                    'languages' => 'required|array',
                    'languages.*.lang' => 'required|string|in:'.$active_languages,
                    'languages.*.title' => 'required|string|max:255',
                    'languages.*.content' => 'required|string',
                    'languages.*.seo_title' => 'required|string|max:255',
                    'languages.*.seo_description' => 'required|string',
                ];
                break;
            case 'PUT':
            case "PATCH":
                $active_languages = implode(',', getActiveISO());
                $rules = [
                    'page' => 'required|integer|exists:pages,id'.$delete_check,

                    'page_url' => 'string|max:255',
                    'is_active' => 'boolean',

                    'languages' => 'array',
                    'languages.*.lang' => 'required|string|in:'.$active_languages,
                    'languages.*.title' => 'required|string|max:255',
                    'languages.*.content' => 'required|string',
                    'languages.*.seo_title' => 'required|string|max:255',
                    'languages.*.seo_description' => 'required|string',

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
        prepareBeforeValidation($this, ['languages'], 'page');
    }
}

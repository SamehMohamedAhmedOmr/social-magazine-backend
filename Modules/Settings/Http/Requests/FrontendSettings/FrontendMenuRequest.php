<?php

namespace Modules\Settings\Http\Requests\FrontendSettings;

use Illuminate\Foundation\Http\FormRequest;

class FrontendMenuRequest extends FormRequest
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
                        'menu' => 'required|integer|exists:frontend_menu,id'.$delete_check,
                    ];
                    break;
                case 'POST':
                    $active_languages = implode(',', getActiveISO());
                    $rules = [
                        'key' => 'required|string|max:255|unique:frontend_menu,key',
                        'order' => 'required|integer|max:50',
                        'navigation_type_id' => 'required|integer|exists:frontend_menu_navigation_type,id|max:255',
                        'data' => 'required|array',
                        'data.*.lang' => 'required|string|in:'.$active_languages,
                        'data.*.name' => 'required|string|max:255',
                    ];
                    break;
                case 'PUT':
                case "PATCH":
                    $active_languages = implode(',', getActiveISO());
                    $rules = [
                        'menu' => 'required|integer|exists:frontend_menu,id'.$delete_check,

                        'key' => 'string|max:255|unique:frontend_menu,key,'.request('menu'),
                        'order' => 'integer|max:50',
                        'navigation_type_id' => 'integer|exists:frontend_menu_navigation_type,id|max:255',
                        'data' => 'array',
                        'data.*.lang' => 'required|string|in:'.$active_languages,
                        'data.*.name' => 'required|string|max:255',
                    ];
                    break;
                default:
                    $rules = [];
                    break;
            }
            return $rules;
        }


    public function attributes()
    {
        return [
            'data' => trans('settings::attributes.data'),
            'data.*.lang' => trans('settings::attributes.lang'),
            'data.*.name' => trans('settings::attributes.name'),
        ];
    }

    public function prepareForValidation()
    {
        prepareBeforeValidation($this, ['data'],'menu');
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

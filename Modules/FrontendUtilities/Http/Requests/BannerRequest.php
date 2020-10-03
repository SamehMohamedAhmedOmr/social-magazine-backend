<?php

namespace Modules\FrontendUtilities\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed id
 * @property mixed banner
 */
class BannerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $delete_check = ',deleted_at,NULL';

        $active_languages = implode(',', getActiveISO());
        /*
            post ---> store
            patch --> update
            delete --> remove , restore
        */
        if (request()->getMethod() == 'POST') {
            return [
                'data' => 'required|array' ,
                'data.*.lang' => 'required|string|max:2|distinct|in:' . $active_languages,
                'data.*.title' => 'string|max:255',
                'data.*.description' => 'string',
                'data.*.alternative' => 'string|max:255',
                'data.*.subject_1' => 'string|max:255',
                'data.*.subject_2' => 'string|max:255',
                'link' => 'string|max:255' ,
                // Image multi/part validation
                'image' => 'required|integer|exists:gallery,id',
                // enable devices [optional true/false]
                'enable_ios' => 'boolean',
                'enable_android' => 'boolean',
                'enable_web' => 'boolean',
                'is_active' => 'boolean',
                // order tos show banners
                'order' => 'integer|max:1000',

                'country_id' => 'integer|exists:countries,id'.$delete_check,

            ];
        }
        if (request()->getMethod() == 'PATCH' || request()->getMethod() == 'PUT') {
            return [
                'data' => 'array' ,
                'data.*.lang' => 'required|string|max:2|distinct|in:' . $active_languages,
                'data.*.title' => 'string|max:255',
                'data.*.description' => 'string',
                'data.*.alternative' => 'string|max:255',
                'data.*.subject_1' => 'string|max:255',
                'data.*.subject_2' => 'string|max:255',
                'link' => 'string|max:255' ,
                // Image multi/part validation
                'image' => 'integer|exists:gallery,id',
                // enable devices [optional true/false]
                'enable_ios' => 'boolean',
                'enable_android' => 'boolean',
                'enable_web' => 'boolean',
                // order tos show banners
                'order' => 'integer|max:1000',
                'is_active' => 'boolean',

                'country_id' => 'integer|exists:countries,id'.$delete_check,

            ];
        }
        if (request()->getMethod() == 'GET' || request()->getMethod() == 'DELETE') {
            return [
                'banner' => 'required|integer|exists:banners,id'.$delete_check,
            ];
        }
        return [];
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
        prepareBeforeValidation($this, ['data'], 'banner');
    }
}

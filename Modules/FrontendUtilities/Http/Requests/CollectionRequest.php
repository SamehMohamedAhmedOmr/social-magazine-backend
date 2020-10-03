<?php

namespace Modules\FrontendUtilities\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed id
 * @property mixed collection
 */
class CollectionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $delete_check = ',deleted_at,NULL';
        $active_languages =  ((request()->getMethod() == "POST" || request()->getMethod() == "PATCH" || request()->getMethod() == "PUT")  ? implode(',', getActiveISO()) : null);
        /*
            post ---> store
            patch --> update
            delete --> remove , restore
        */
        if (request()->getMethod() == 'POST') {
            return [
                'data' => 'array|required' ,
                'data.*.lang' => 'string|max:2|distinct|required|in:' . $active_languages,
                'data.*.title' => 'string|max:255|required',
                // enable devices [optional true/false]
                'enable_ios' => 'boolean',
                'enable_android' => 'boolean',
                'enable_web' => 'boolean',
                'is_active' => 'boolean' ,
                // order tos show banners
                'order' => 'integer|max:1000|required' ,
                // TODO MockUP validate array of products ids , [add validation exists in product table]
                'products' => 'required|array' ,
                'products.*' => 'required|integer|exists:products,id'.$delete_check,

                'country_id' => 'integer|exists:countries,id'.$delete_check,

            ];
        }
        if (request()->getMethod() == 'PATCH' || request()->getMethod() == 'PUT') {
            return [
                'collection' => 'required|integer|exists:collection,id',
                'data' => 'array' ,
                'data.*.lang' => 'string|max:2|distinct|required|in:' . $active_languages,
                'data.*.title' => 'string|max:255|required',
                // enable devices [optional true/false]
                'enable_ios' => 'boolean',
                'enable_android' => 'boolean',
                'enable_web' => 'boolean',
                'is_active' => 'boolean' ,
                // order tos show banners
                'order' => 'integer|max:1000' ,
                // TODO MockUP validate array of products ids , [add validation exists in product table]
                'products' => 'array' ,
                'products.*' => 'required|integer|exists:products,id'.$delete_check,

                'country_id' => 'integer|exists:countries,id'.$delete_check,

            ];
        }
        if (request()->getMethod() == 'DELETE' || request()->getMethod() == 'GET') {
            return [
                'collection' => 'required|integer|exists:collection,id'.$delete_check,
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
        prepareBeforeValidation($this, ['data'], 'collection');
    }
}

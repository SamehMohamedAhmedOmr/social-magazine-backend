<?php

namespace Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property mixed time_section
 */
class TimeSectionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $delete_check = ',deleted_at,NULL';

        /*
            post ---> store
            patch --> update
            delete --> remove , restore
        */
        $active_languages =  ((request()->getMethod() == "POST" || request()->getMethod() == "PATCH" || request()->getMethod() == "PUT")  ? implode(',', getActiveISO()) : null);
        if (request()->getMethod() == 'POST') {
            return [
                'data' => 'array|required' ,
                'data.*.lang' => 'required|string|max:2|distinct|in:' . $active_languages,
                'data.*.name' => 'string|max:255|required',
                'from' => 'required|date_format:H:i',
                'to' => 'required|date_format:H:i',
                'is_active' => 'required|boolean',
                'country_id' => 'integer|exists:countries,id'.$delete_check,

                'days' => 'required|array' ,
                'days.*' => ['required' , 'integer' , 'exists:days,id' , Rule::unique('time_section_days', 'day_id')->where(function ($query) {
                    return $query->where('deleted_at', null);
                })],
            ];
        }
        if (request()->getMethod() == 'PATCH' || request()->getMethod() == 'PUT') {
            return [
                'data' => 'array' ,
                'data.*.lang' => 'string|max:2|distinct|required|in:' . $active_languages,
                'data.*.name' => 'string|max:255|required',
                'from' => 'date_format:H:i',
                'to' => 'date_format:H:i',
                'is_active' => 'boolean' ,
                'country_id' => 'integer|exists:countries,id'.$delete_check,
                'days' => 'array' ,
                'days.*' =>  [ 'integer' , Rule::unique('time_section_days', 'day_id')->where(function ($query) {
                    return $query->where('time_section_id', '!=', request('time_section'))->where('deleted_at', '==', null);
                })],
            ];
        }
        if (request()->getMethod() == 'DELETE' || request()->getMethod() == 'GET') {
            return [
                'time_section' => 'required|integer|exists:time_sections,id'.$delete_check,
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
        prepareBeforeValidation($this, ['data' , 'days'], 'time_section');
    }
}

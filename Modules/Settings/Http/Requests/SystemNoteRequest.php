<?php

namespace Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SystemNoteRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $active_languages = implode(',', getActiveISO());

        $delete_check = ',deleted_at,NULL';

        switch ($this->getMethod()) {
            case 'GET':
            case 'DELETE':
                $rules = [
                    'system_note' => 'required|integer|exists:system_notes,id'.$delete_check
                ];
                break;

            case 'POST':
                $rules = [
                    'note_key' => 'required|string|max:64|unique:system_notes,note_key',

                    'data' => 'required|array',
                    'data.*.lang' => 'required|string|in:'.$active_languages,
                    'data.*.note_body' => 'required|string',
                    'country_id' => 'integer|exists:countries,id'.$delete_check,

                ];
                break;
            case 'PUT':
            case 'PATCH':
                $rules = [
                    'system_note' => 'required|integer|exists:system_notes,id'.$delete_check,
                    'note_key' => 'string|max:64|unique:system_notes,note_key,'.request('system_note'),

                    'data' => 'array',
                    'data.*.lang' => 'string|in:'.$active_languages,
                    'data.*.note_body' => 'string|max:512',
                    'country_id' => 'integer|exists:countries,id'.$delete_check,

                ];
                break;
            default:
                $rules = [];
                break;
        }

        return  $rules;
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
        prepareBeforeValidation($this, [], 'system_note');
    }
}

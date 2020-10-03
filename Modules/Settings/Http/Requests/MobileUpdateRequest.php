<?php

namespace Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MobileUpdateRequest extends FormRequest
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
                    'mobile_update' => 'required|integer|exists:mobile_updates,id'.$delete_check,
                ];
                break;
            case 'POST':
                $rules = [
                    'device_type' => 'required|string|in:ANDROID,IOS',
                    'application_version' => 'required|string|max:32',
                    'build_number' => [
                        'required',
                        'integer',
                        'min:1',
                        'max:999999',
                        Rule::unique('mobile_updates', 'build_number')->where(function ($query) {
                            return $query->where('device_type', request('device_type'));
                        })
                    ],
                    'is_active' => 'boolean',
                    'force_update' => 'boolean',
                    'release_date' => 'required|date_format:Y-m-d',
                ];
                break;
            case 'PUT':
            case "PATCH":
                $rules = [
                    'mobile_update' => 'required|integer|exists:mobile_updates,id'.$delete_check,

                    'device_type' => 'string|in:ANDROID,IOS',
                    'application_version' => 'string|max:32',
                    'build_number' => [
                        'integer',
                        'min:1',
                        'max:999999',
                        Rule::unique('mobile_updates', 'build_number')->where(function ($query) {
                            return $query->where('device_type', request('device_type'));
                        })->ignore(request('mobile_update'))
                    ],
                    'is_active' => 'boolean',
                    'force_update' => 'boolean',
                    'release_date' => 'date_format:Y-m-d',
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
        prepareBeforeValidation($this, [], 'mobile_update');
    }
}

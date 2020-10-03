<?php

namespace Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LanguageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validation = [];

        if (request()->getMethod() == 'POST') {
            $validation = [
                'name' => 'required|string|min:2|max:40',
                'iso' => 'required|string|min:1|max:5|unique:languages,iso',
                'is_active' => 'required|in:0,1,true,false',
            ];
        } elseif (request()->getMethod() == 'PUT' || request()->getMethod() == 'PATCH') {
            $validation = [
                'name' => 'nullable|string|min:2|max:40',
                'iso' => 'nullable|string|min:1|max:5|unique:languages,iso,'.request()->language,
                'is_active' => 'nullable|in:0,1,true,false',
            ];

            if (array_intersect(array_keys($validation), array_keys(request()->all())) == []) {
                $validator = Validator::make([], []);
                $validator->errors()->add('key', 'no keys sent');
                throw new ValidationException($validator, 'Bad Request');
            }
        }

        return $validation;
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

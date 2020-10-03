<?php

namespace Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Settings\Rules\FontValidation;

class FontsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
        {
            switch ($this->getMethod()) {
                case 'POST':
                    $rules = [
                        'name' => 'required|string|max:255',
                        'font_file' =>  [
                            'bail',
                            'required',
                            'file',
                            new FontValidation
                        ],
                        'type' =>  'required|in:0,1,2',
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
}

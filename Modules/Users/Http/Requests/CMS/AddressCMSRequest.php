<?php

namespace Modules\Users\Http\Requests\CMS;

use Illuminate\Foundation\Http\FormRequest;

class AddressCMSRequest extends FormRequest
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
                $default = [
                    'address' => 'required|integer|exists:address,id'.$delete_check,
                ];
                break;
            default:
                $default = [];
                break;
        }

        return $default;
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
        prepareBeforeValidation($this, [], 'address');
    }
}

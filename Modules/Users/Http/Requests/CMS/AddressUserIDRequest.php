<?php

namespace Modules\Users\Http\Requests\CMS;

use Illuminate\Foundation\Http\FormRequest;

class AddressUserIDRequest extends FormRequest
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
                    'user_id' => 'required|integer|exists:users,id'.$delete_check,
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
}

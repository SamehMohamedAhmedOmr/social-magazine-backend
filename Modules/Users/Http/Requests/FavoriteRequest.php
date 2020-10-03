<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FavoriteRequest extends FormRequest
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
            case 'POST':
                $rules = [
                    'product_id' => 'required|integer|exists:products,id'.$delete_check
                ];
                break;
            case 'DELETE':
                $rules = [
                    'favorite_id' => 'required_without:product_id|integer|exists:favorites,id'.$delete_check,
                    'product_id' => 'required_without:favorite_id|integer|exists:products,id'.$delete_check
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

    protected function prepareForValidation()
    {
        if (!isset($this->product_id)) {
            prepareBeforeValidation($this, [], 'favorite_id');
        }
    }
}

<?php

namespace Modules\WareHouse\Http\Requests\CMS\Order;

use Illuminate\Foundation\Http\FormRequest;

class OrderNoteRequest extends FormRequest
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
            delete --> remove
        */
        if (request()->getMethod() == 'POST') {
            return [
                'order_id' => 'required|integer|exists:orders,id'.$delete_check,
                'note' => 'required|string|max:2000'
            ];
        }
        if (request()->getMethod() == 'PATCH' || request()->getMethod() == 'PUT') {
            return [
                'orders_note' => 'required|integer|exists:order_notes,id'.$delete_check,
                'order_id' => 'integer|exists:orders,id'.$delete_check,
                'note' => 'string|max:2000',
            ];
        }
        if (request()->getMethod() == 'GET' || request()->getMethod() == 'DELETE') {
            return [
                'orders_note' => 'required|integer|exists:order_notes,id'.$delete_check,
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
        prepareBeforeValidation($this, ['data'], 'orders_note');
    }
}

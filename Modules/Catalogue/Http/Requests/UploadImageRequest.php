<?php

namespace Modules\Catalogue\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $delete_check = ',deleted_at,NULL';
        return [
            'product_id' => 'required|integer|exists:products,id'.$delete_check,
            'images' => 'required|array',
            'images.*' => 'required|integer|exists:gallery,id',
        ];
    }
}

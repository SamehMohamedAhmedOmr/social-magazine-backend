<?php

namespace Modules\Gallery\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GalleryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->getMethod()) {

            case 'GET':
            case 'DELETE':
                $default = [
                    'gallery_id' => 'required|integer|exists:gallery,id'
                ];
                break;
            case 'POST':
                $default = [
                    'gallery_type' => 'required|string|exists:gallery_types,key',
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
        prepareBeforeValidation($this, [], 'gallery_id');
    }
}

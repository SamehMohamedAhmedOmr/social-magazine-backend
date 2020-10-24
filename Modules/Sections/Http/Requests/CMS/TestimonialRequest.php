<?php

namespace Modules\Sections\Http\Requests\CMS;

use Illuminate\Foundation\Http\FormRequest;

class TestimonialRequest extends FormRequest
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
                $default = [
                    'testimonial' => 'required|integer|exists:magazine_testimonial,id' . $delete_check,
                ];
                break;
            case 'POST':
                $default = [
                    'name' => 'required|string|max:255',
                    'content' => 'required|string|max:65535',
                    'stars' => 'required|integer|min:1|max:5',
                    'is_active' => 'boolean',

                    'image_id' => 'required|integer|exists:gallery,id',
                ];
                break;
            case 'PUT':
                $default = [
                    'testimonial' => 'required|integer|exists:magazine_testimonial,id' . $delete_check,

                    'name' => 'nullable|string|max:255',
                    'content' => 'nullable|string|max:65535',
                    'stars' => 'nullable|integer|min:1|max:5',

                    'is_active' => 'boolean',

                    'image_id' => 'integer|exists:gallery,id',
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

    public function attributes()
    {
        return [
            'testimonial' => 'التوصية',
            'stars' => 'التقييم',
            'image_id' => 'الصورة',
        ];
    }

    protected function prepareForValidation()
    {
        prepareBeforeValidation($this, [], 'testimonial');
    }
}

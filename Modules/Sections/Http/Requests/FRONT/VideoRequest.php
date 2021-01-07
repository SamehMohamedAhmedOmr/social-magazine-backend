<?php

namespace Modules\Sections\Http\Requests\FRONT;

use Illuminate\Foundation\Http\FormRequest;

class VideoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $delete_check = ',deleted_at,NULL';
        return [
            'slug' => 'required|string|exists:videos,slug' . $delete_check,
        ];
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

    public function messages()
    {
        return [
            'slug.*' => 'هذا الفيديو غير مسجل لدينا'
        ];
    }

    public function attributes()
    {
        return [
            'slug' => 'الفيديو'
        ];
    }

    protected function prepareForValidation()
    {
        prepareBeforeValidation($this, [], 'slug');
    }

}

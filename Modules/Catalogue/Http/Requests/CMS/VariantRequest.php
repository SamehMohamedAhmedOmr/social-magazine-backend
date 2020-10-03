<?php

namespace Modules\Catalogue\Http\Requests\CMS;

use Illuminate\Foundation\Http\FormRequest;

class VariantRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $required_sometimes = $this->getMethod() == 'POST' ? 'required|' : 'sometimes|';

        return [
            'names' => $required_sometimes.'array',
            'names.*.name' => $required_sometimes.'string|max:200',
            'names.*.language' => $required_sometimes."string|max:4|exists:languages,iso",
            'is_color' => 'sometimes|boolean',
            'is_active' => $required_sometimes.'boolean',
        ];
    }
}

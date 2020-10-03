<?php

namespace Modules\Catalogue\Http\Requests\CMS;

use Illuminate\Foundation\Http\FormRequest;

class VariantValueRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $required_sometimes = $this->getMethod() == 'POST' ? 'required|' : 'sometimes|';
        $unique_or_not = $this->getMethod() == 'POST' ? 'unique:variant_values,code' : 'unique:variant_values,code,'.$this->variant_value;
        return [
            'names' => $required_sometimes.'array',
            'names.*.name' => $required_sometimes.'string|max:200',
            'names.*.language' => $required_sometimes."string|max:4|exists:languages,iso",
            'value' => $required_sometimes.'string|min:2',
            'code' => $required_sometimes.'string|min:1|'.$unique_or_not,
            'variant_id' => $required_sometimes."exists:variants,id",
            'palette_image' => 'nullable|image|mimes:jpeg,jpg,png',
            'is_active' => $required_sometimes.'boolean',
        ];
    }
}

<?php

namespace Modules\FacebookCatalogue\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FacebookCatalogueSettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $required_or_not = request()->method == 'POST' ? 'required' : 'sometimes';
        return [
            'android_package_name' => $required_or_not.'|string|max:254',
            'android_fallback_link' => $required_or_not.'|string|max:254',
            'android_min_package_version_code' => $required_or_not.'|string|max:254',
            'ios_bundle_id' => $required_or_not.'|string|max:254',
            'ios_fallback_link' => $required_or_not.'|string|max:254',
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
}

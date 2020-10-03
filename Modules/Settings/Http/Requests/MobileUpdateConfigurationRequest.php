<?php

namespace Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MobileUpdateConfigurationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'device_os' => 'nullable|string|in:ANDROID,IOS',
            'app_build_number' => 'nullable|integer',
            'app_version' => 'nullable|string'
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

    public function prepareForValidation()
    {
        $device_os = request()->header('device_os');
        $app_build_number = request()->header('app_build_number');
        $app_version = request()->header('app_version');

        $input = $this->all();
        $input['device_os'] = $device_os;
        $input['app_build_number'] = $app_build_number;
        $input['app_version'] = $app_version;

        $this->replace($input);
    }
}

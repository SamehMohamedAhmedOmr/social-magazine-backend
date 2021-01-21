<?php

namespace Modules\Sections\Http\Requests\CMS;

use Illuminate\Foundation\Http\FormRequest;

class MagazineInformationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'vision' => 'nullable|string|max:65535',
            'mission' => 'nullable|string|max:65535',
            'address' => 'nullable|string|max:65535',
            'phone' => 'nullable|string|max:255',
            'fax_number' => 'nullable|string|max:255',
            'email' => 'nullable|email:rfc,filter',
            'postal_code' => 'nullable|string|max:255',
            'magazine_link' => 'nullable|active_url|max:255',

            'facebook' => 'nullable|active_url|max:255',
            'twitter' => 'nullable|active_url|max:255',
            'instgram' => 'nullable|active_url|max:255',
            'whatsapp' => 'nullable|active_url|max:255',
        ];
    }

    public function attributes()
    {
        return [
            'vision' => 'الرؤية',
            'mission' => 'الرسالة',
            'address' => 'العنوان',
            'phone' => 'الهاتف',
            'fax_number' => 'الفاكس',
            'email' => 'البريد الالكتروني',
            'postal_code' => 'الكود البريدي',
            'magazine_link' => 'لينك المجلة',

            'facebook' => 'فيسبوك',
            'twitter' => 'تويتر',
            'instgram' => 'انستجرام',
            'whatsapp' => 'واتساب',
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

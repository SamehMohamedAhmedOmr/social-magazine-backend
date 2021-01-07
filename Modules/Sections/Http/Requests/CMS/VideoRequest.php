<?php

namespace Modules\Sections\Http\Requests\CMS;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Sections\Rules\YoutubeVideoRule;

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

        switch ($this->getMethod()) {
            case 'GET':
            case 'DELETE':
                $default = [
                    'video' => 'required|integer|exists:videos,id' . $delete_check,
                ];
                break;
            case 'POST':
                $default = [
                    'title' => 'required|string|max:255',
                    'content' => 'required|string|max:65535',
                    'link' => [
                        'required',
                        'max:255',
                        'active_url',
                        new YoutubeVideoRule
                    ],
                    'is_active' => 'boolean',
                ];
                break;
            case 'PUT':
                $default = [
                    'video' => 'required|integer|exists:videos,id' . $delete_check,

                    'title' => 'nullable|string|max:255',
                    'content' => 'nullable|string|max:65535',
                    'link' => [
                        'max:255',
                        'active_url',
                        new YoutubeVideoRule
                    ],
                    'is_active' => 'boolean',
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
            'video' => 'الفيديو',
            'title' => 'العنوان',
            'date' => 'التاريخ',
            'link' => 'لينك',
        ];
    }

    protected function prepareForValidation()
    {
        prepareBeforeValidation($this, [], 'video');
    }
}

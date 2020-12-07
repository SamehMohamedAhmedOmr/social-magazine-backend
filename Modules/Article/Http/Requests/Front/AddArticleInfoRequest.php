<?php

namespace Modules\Article\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;

class AddArticleInfoRequest extends FormRequest
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
            'article_id' => 'required|integer|exists:articles,id' . $delete_check,

            'title_ar' => 'string|max:255',
            'title_en' => 'string|max:255',

            'content_ar' => 'string|max:1050',
            'content_en' => 'string|max:1050',

            'keywords_en' => 'array|max:5',
            'keywords_en.*' => 'string|max:255',

            'keywords_ar' => 'array|max:5',
            'keywords_ar.*' => 'string|max:255',
        ];
    }

    public function attributes()
    {
        return [
            'title_ar' => 'عنوان المقالة باللغة العربية',
            'title_en' => 'عنوان المقالة باللغة الانجليزية',

            'content_ar' => 'المستخلص باللغة العربية',
            'content_en' => 'المستخلص باللغة الانجليزية',

            'keywords_ar' => 'الكلمات الرئيسية باللغة العربية',
            'keywords_en' => 'الكلمات الرئيسية باللغة الانجليزية',
            'keywords_ar.*' => 'الكلمات الرئيسية باللغة العربية',
            'keywords_en.*' => 'الكلمات الرئيسية باللغة الانجليزية',
        ];
    }

    public function messages()
    {
        return [
            'keywords_en.max' => 'الكلمات الرئيسية باللغة العربية لا يجب ان يزيد عن 5 كلمات',
            'keywords_ar.max' => 'الكلمات الرئيسية باللغة الانجليزية لا يجب ان يزيد عن 5 كلمات',

            'content_ar.max' => 'المستخلص باللغة العربية لا يجب ان يزيد عن 150 كلمة',
            'content_en.max' => 'المستخلص باللغة الانجليزية لا يجب ان يزيد عن 150 كلمة',
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

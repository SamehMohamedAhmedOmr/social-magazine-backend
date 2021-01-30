<?php

namespace Modules\Article\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;

class AddArticleRequest extends FormRequest
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
            'article_type_id' => 'required|integer|exists:article_type,id' . $delete_check,
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
        ];
    }

    public function attributes()
    {
        return [
            'article_type_id' => 'نوع المقالة',
            'title_ar' => 'عنوان المقالة باللغة العربية',
            'title_en' => 'عنوان المقالة باللغة الانجليزية',
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

<?php

namespace Modules\Article\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;

class ArticleAttachmentsRequest extends FormRequest
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
                    'article_attachment' => 'required|integer|exists:article_attachment,id' . $delete_check,
                    'article_id' => 'required|integer|exists:articles,id' . $delete_check,
                ];
                break;
            case 'POST':
                $default = [
                    'article_id' => 'required|integer|exists:articles,id' . $delete_check,

                    'description' => 'required|string|max:65535',
                    'attachment_type_id' => 'required|integer|exists:attachments_type,id' . $delete_check,
                    'file' => 'required|file|mimes:pdf,doc,docx|max:5120',
                ];
                break;
            default:
                $default = [];
                break;
        }

        return $default;
    }


    public function attributes()
    {
        return [
            'article_attachment' => 'الملف',
            'attachment_type_id' => 'نوع الملف',

            'description' => 'الوصف',
            'file' => 'الملف',
            'article_id' => 'المقال',
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

    protected function prepareForValidation()
    {
        prepareBeforeValidation($this, [], 'article_attachment');
    }
}

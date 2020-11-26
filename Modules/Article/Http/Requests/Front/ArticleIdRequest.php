<?php

namespace Modules\Article\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;

class ArticleIdRequest extends FormRequest
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

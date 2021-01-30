<?php

namespace Modules\Article\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Users\Facades\UsersErrorsHelper;

class ArticleSuggestedJudgesRequest extends FormRequest
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
                    'article_judge' => 'required|integer|exists:article_suggested_judge,id' . $delete_check,
                    'article_id' => 'required|integer|exists:articles,id' . $delete_check,
                ];
                break;
            case 'POST':
                $default = [
                    'article_id' => 'required|integer|exists:articles,id' . $delete_check,

                    'first_name' => 'required|string|regex:' . UsersErrorsHelper::regexName() . '|max:255',
                    'family_name' => 'required|string|regex:' . UsersErrorsHelper::regexName() . '|max:255',

                    'email' => 'required|email:rfc,filter',

                    'alternative_email' => 'nullable|email:rfc,filter',
                    'phone_number' => 'required|string|min:4|max:14',

                    'country_id' => 'required|integer|exists:countries,id' . $delete_check,
                    'gender_id' => 'required|integer|exists:genders,id' . $delete_check,
                    'title_id' => 'required|integer|exists:titles,id' . $delete_check,
                    'educational_level_id' => 'required|integer|exists:educational_levels,id' . $delete_check,
                    'educational_degree_id' => 'required|integer|exists:educational_degrees,id' . $delete_check,

                    'address' => 'nullable|string|max:255',
                ];
                break;
            case 'PUT':
                $default = [
                    'article_judge' => 'required|integer|exists:article_suggested_judge,id' . $delete_check,

                    'article_id' => 'required|integer|exists:articles,id' . $delete_check,

                    'first_name' => 'nullable|string|regex:' . UsersErrorsHelper::regexName() . '|max:255',
                    'family_name' => 'nullable|string|regex:' . UsersErrorsHelper::regexName() . '|max:255',


                    'email' => 'nullable|email:rfc,filter',
                    'alternative_email' => 'nullable|email:rfc,filter',
                    'phone_number' => 'nullable|string|min:4|max:14',

                    'country_id' => 'nullable|integer|exists:countries,id' . $delete_check,
                    'gender_id' => 'nullable|integer|exists:genders,id' . $delete_check,
                    'title_id' => 'nullable|integer|exists:titles,id' . $delete_check,
                    'educational_level_id' => 'nullable|integer|exists:educational_levels,id' . $delete_check,
                    'educational_degree_id' => 'nullable|integer|exists:educational_degrees,id' . $delete_check,

                    'address' => 'nullable|string|max:255',
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
            'article_judge' => 'المؤلف',
            'first_name' => 'الاسم الاول',
            'family_name' => 'اسم العائلة',

            'email' => 'البريد الالكتروني',
            'alternative_email' => 'البريد الالكتروني البديل',
            'phone_number' => 'رقم الموبايل',

            'country_id' => 'الدولة',
            'gender_id' => 'النوع',
            'title_id' => 'اللقب',
            'educational_level_id' => 'المستوى التعليمي',
            'educational_degree_id' => 'الدرجة العلمية',

            'address' => 'العنوان',

            'article_id' => 'المقال'

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
        prepareBeforeValidation($this, [], 'article_judge');
    }
}

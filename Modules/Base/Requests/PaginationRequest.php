<?php

namespace Modules\Base\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaginationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (request()->getMethod() == 'GET') {
            return [
                'is_pagination' => 'boolean', // 0 => all , 1 pagination
                'per_page' => 'integer|min:1|max:500',
                'sort_key' => 'string|max:255|in:id,created_at,updated_at,order',
                'sort_order' => 'string|in:asc,desc',
                'is_active' => 'boolean',
                'search_key' => 'string|max:255'
            ];
        }
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

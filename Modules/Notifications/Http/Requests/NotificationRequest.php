<?php

namespace Modules\Notifications\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:200',
            'body' => 'required|string|max:1024',
            'link' => 'required|url',
            'color' => 'required|string',
            'target' => 'required_without:users|in:ANDROID,IOS,WEB,ALL',
            'users' => 'required_without:target|array',
            'users.*' => 'required|integer|exists:users,id',
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

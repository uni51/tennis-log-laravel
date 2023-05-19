<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemoEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'id' => ['required', 'int'],
            'category_id' => ['required', 'int'],
            'title' => ['required'],
            'body' => ['required'],
            'tags' => ['nullable', 'array']
        ];
    }

    public function messages()
    {
        return [
            'required' => '必須入力です。',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemoPostRequest extends FormRequest
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
            'title'       => ['required', 'min:3', 'max:100'],
            'body'        => ['required', 'min:3', 'max:3000'],
            'category_id' => ['required', 'int', 'between:1,8'],
            'status_id'   => ['required', 'int', 'between:0,4'],
            'tags'        => ['nullable'],
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'タイトル',
            'body' => '内容',
        ];
    }
}

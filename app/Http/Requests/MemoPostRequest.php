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
    public function authorize(): bool
    {
        return true; // リクエストの許可を認可
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title'       => ['required', 'min:3', 'max:100'],
            'body'        => ['required', 'min:3', 'max:3000'],
            'category_id' => ['required', 'int', 'between:1,8'],
            'status_id'   => ['required', 'int', 'between:0,4'],
            'tags'        => ['nullable', 'array'],
        ];
    }

    /**
     * バリデーションエラーのカスタム属性の取得
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'title' => 'タイトル',
            'body' => '内容',
        ];
    }
}

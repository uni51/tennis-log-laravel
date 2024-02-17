<?php

namespace App\Http\Requests\PublicMemos;

use Illuminate\Foundation\Http\FormRequest;

class PublicMemoSearchRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'q' => 'nullable|string|max:255', // 例: 文字列で最大255文字
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
            'q' => '検索キーワード',
        ];
    }

    /**
     * Get the validation error messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'q.string' => '検索キーワードはstring型で入力してください。',
            'q.max' => '検索キーワードは255文字以内で入力してください。',
        ];
    }
}

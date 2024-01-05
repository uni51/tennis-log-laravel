<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class PublicMemoSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // リクエストの許可を認可
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'q' => 'nullable|string|max:2', // 例: 文字列で最大255文字
        ];
    }

    public function attributes()
    {
        return [
            'q' => '検索キーワード',
        ];
    }

    public function messages()
    {
        return [
            'q.max' => '検索キーワードは255文字以内で入力してください。',
        ];
    }
}

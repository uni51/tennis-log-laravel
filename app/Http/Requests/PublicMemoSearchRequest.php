<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'q' => 'nullable|string|max:255', // 例: 文字列で最大255文字
        ];
    }
}

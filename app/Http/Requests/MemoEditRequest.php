<?php

namespace App\Http\Requests;

use App\Enums\MemoStatusType;
use Illuminate\Foundation\Http\FormRequest;

class MemoEditRequest extends FormRequest
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
            'id' => ['required', 'int', 'exists:memos,id'],
            'title' => ['required'],
            'body' => ['required'],
            'category_id' => ['required', 'int'],
            'tags' => ['nullable', 'array'],
            'status_id' => ['required', 'int', 'between:'.MemoStatusType::DRAFT.','.MemoStatusType::UN_PUBLISHING],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge(['id' => $this->route('id')]);
    }

    /**
     * Get the validation error messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => '必須入力です。',
        ];
    }
}

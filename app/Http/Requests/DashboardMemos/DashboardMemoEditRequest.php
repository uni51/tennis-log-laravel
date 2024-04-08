<?php

namespace App\Http\Requests\DashboardMemos;

use App\Enums\MemoStatusType;
use App\Rules\AppropriateContent;
use Illuminate\Foundation\Http\FormRequest;

class DashboardMemoEditRequest extends FormRequest
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
            'id' => ['required', 'int'],
            'title' => ['required', 'min:3', new AppropriateContent()],
            'body' => ['required', 'min:3', new AppropriateContent()],
            'category_id' => ['required', 'int'],
            'tags' => ['nullable', 'array'],
            'status_id' => ['required', 'int', 'between:'.MemoStatusType::DRAFT.','.MemoStatusType::WAITING_FOR_FIX],
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
}

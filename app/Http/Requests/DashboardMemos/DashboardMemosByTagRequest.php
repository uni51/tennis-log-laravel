<?php

namespace App\Http\Requests\DashboardMemos;

use Illuminate\Foundation\Http\FormRequest;

class DashboardMemosByTagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool // リクエストの許可を認可
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
            'tag' => ['required', 'string'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge(['tag' => $this->route('tag')]);
    }
}

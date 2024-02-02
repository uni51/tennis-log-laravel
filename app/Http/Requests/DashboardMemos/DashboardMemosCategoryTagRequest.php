<?php

namespace App\Http\Requests\DashboardMemos;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidCategory;

class DashboardMemosCategoryTagRequest extends FormRequest
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
            'categoryId' => [ 'required', 'int', new ValidCategory],
            'tag' => [ 'required', 'string'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'categoryId' => $this->route('categoryId'),
            'tag' => $this->route('tag')
        ]);
    }
}

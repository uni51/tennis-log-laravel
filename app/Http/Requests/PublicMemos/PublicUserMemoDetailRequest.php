<?php

namespace App\Http\Requests\PublicMemos;

use Illuminate\Foundation\Http\FormRequest;

class PublicUserMemoDetailRequest extends FormRequest
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
            'nickName' => ['required', 'string'],
            'id' => ['required', 'int'],
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
            'nickName' => $this->route('nickName'),
            'id' => $this->route('id')
        ]);
    }
}

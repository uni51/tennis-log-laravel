<?php

namespace App\Http\Requests\NicknameMemos;

use App\Rules\Memo\ValidCategory;
use Illuminate\Foundation\Http\FormRequest;

class NicknameMemoListByCategoryRequest extends FormRequest
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
            'nickname' => ['required', 'string'],
            'category_id' => ['required', 'int', new ValidCategory()],
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
            'nickname' => $this->route('nickname'),
            'category_id' => $this->route('category_id')
        ]);
    }
}

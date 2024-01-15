<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidCategory;

class DashboardMemosCategoryTagRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'categoryId' => $this->route('categoryId'),
            'tag' => $this->route('tag')
        ]);
    }

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
            'categoryId' => [ 'required', 'int', new ValidCategory],
            'tag' => [ 'required', 'string'],
        ];
    }
}

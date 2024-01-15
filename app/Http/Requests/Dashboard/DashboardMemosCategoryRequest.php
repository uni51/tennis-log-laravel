<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidCategory;

class DashboardMemosCategoryRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['categoryId' => $this->route('categoryId')]);
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
        ];
    }
}

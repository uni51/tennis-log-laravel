<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class DashboardMemosTagRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        $this->merge(['tag' => $this->route('tag')]);
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
            'tag' => [ 'required', 'string'],
        ];
    }
}

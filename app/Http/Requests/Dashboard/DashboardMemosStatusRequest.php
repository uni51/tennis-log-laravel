<?php

namespace App\Http\Requests\Dashboard;

use App\Enums\MemoStatusType;
use Illuminate\Foundation\Http\FormRequest;

class DashboardMemosStatusRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['status' => $this->route('status')]);
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
            'status' => 'required | int | between:'.MemoStatusType::DRAFT.','.MemoStatusType::UN_PUBLISHING,
        ];
    }
}

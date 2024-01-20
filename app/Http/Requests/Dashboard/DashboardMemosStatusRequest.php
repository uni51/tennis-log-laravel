<?php

namespace App\Http\Requests\Dashboard;

use App\Enums\MemoStatusType;
use Illuminate\Foundation\Http\FormRequest;

class DashboardMemosStatusRequest extends FormRequest
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
            'status' => 'required | int | between:'.MemoStatusType::DRAFT.','.MemoStatusType::UN_PUBLISHING,
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge(['status' => $this->route('status')]);
    }
}

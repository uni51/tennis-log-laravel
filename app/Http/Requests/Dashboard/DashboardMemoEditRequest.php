<?php

namespace App\Http\Requests\Dashboard;

use App\Enums\MemoStatusType;
use App\Exceptions\MemoNotFoundException;
use App\Models\Memo;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class DashboardMemoEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @throws MemoNotFoundException
     * @throws AuthorizationException
     */
    public function authorize(): bool
    {
        $dashboardMemo = Memo::find($this->route('id'));
        if (!$dashboardMemo) {
            throw new MemoNotFoundException('指定されたIDのメモが見つかりません。');
        }
        $validMemoOwner = $this->user()->id === $dashboardMemo->user_id;
        if (!$validMemoOwner) {
            throw new AuthorizationException('権限がありません。');
        }
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
            'title' => ['required'],
            'body' => ['required'],
            'category_id' => ['required', 'int'],
            'tags' => ['nullable', 'array'],
            'status_id' => ['required', 'int', 'between:'.MemoStatusType::DRAFT.','.MemoStatusType::UN_PUBLISHING],
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

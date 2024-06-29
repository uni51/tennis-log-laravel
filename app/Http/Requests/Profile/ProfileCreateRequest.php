<?php

namespace App\Http\Requests\Profile;

use App\Rules\Memo\AppropriateContent;
use Illuminate\Foundation\Http\FormRequest;

class ProfileCreateRequest extends FormRequest
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
            'name'            => ['required', 'max:100', new AppropriateContent()],
            'career_id'       => ['required', 'int', 'between:1,15'],
            'gender_id'       => ['required', 'int', 'between:1,2'],
            'dominantHand_id' => ['required', 'int', 'between:1,3'],
            'playFrequency_id'=> ['required', 'int', 'between:1,7'],
            'tennisLevel_id'  => ['required', 'int', 'between:1,8'],
        ];
    }

    /**
     * バリデーションエラーのカスタム属性の取得
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'title' => 'タイトル',
            'body' => '内容',
        ];
    }
}

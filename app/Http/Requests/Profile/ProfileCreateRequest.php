<?php

namespace App\Http\Requests\Profile;

use App\Rules\Memo\AppropriateContent;
use App\Rules\Profile\ValidCareerType;
use App\Rules\Profile\ValidDominantHandType;
use App\Rules\Profile\ValidGender;
use App\Rules\Profile\ValidPlayFrequencyType;
use App\Rules\Profile\ValidTennisLevelType;
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
            // 'name'            => ['required', 'max:100', new AppropriateContent()],
            'career_id'       => ['required', 'int', new ValidCareerType()],
            'gender_id'       => ['required', 'int', new ValidGender()],
            'dominantHand_id' => ['required', 'int', new ValidDominantHandType()],
            'playFrequency_id'=> ['required', 'int', new ValidPlayFrequencyType()],
            'tennisLevel_id'  => ['required', 'int', new ValidTennisLevelType()],
            'birthYear'       => ['required', 'int'],
            'birthMonth'      => ['required', 'int'],
            'birthDay'        => ['required', 'int'],
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
            'career_id'         => 'テニス歴',
            'gender_id'         => '性別',
            'dominantHand_id'   => '利き手',
            'playFrequency_id'  => 'プレイ頻度',
            'tennisLevel_id'    => 'レベル',
        ];
    }
}

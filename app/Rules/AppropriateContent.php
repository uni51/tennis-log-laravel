<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AppropriateContent implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 不適切と考えられる単語のリスト。
     *
     * @var array
     */
    protected $inappropriateWords = [
        'SEX',
        '不適切な単語2',
        // その他の不適切な単語やフレーズ
    ];

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach ($this->inappropriateWords as $word) {
            if (stripos($value, $word) !== false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute contains inappropriate content.';
    }
}

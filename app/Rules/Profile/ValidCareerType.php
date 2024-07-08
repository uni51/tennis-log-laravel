<?php

namespace App\Rules\Profile;

use App\Enums\Profile\CareerType;
use Illuminate\Contracts\Validation\Rule;

class ValidCareerType implements Rule
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
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        // $valueが1〜15のいずれかの値であり、かつ0でないことを検証
        return in_array($value, CareerType::getValues()) && $value != 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return '無効なテニス歴です。';
    }
}

<?php

namespace App\Rules\Profile;

use App\Enums\Profile\PlayFrequencyType;
use Illuminate\Contracts\Validation\Rule;

class ValidPlayFrequencyType implements Rule
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
        // $valueが1〜7のいずれかの値であり、かつ0でないことを検証
        return in_array($value, PlayFrequencyType::getValues()) && $value != 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return '無効なプレー頻度です。';
    }
}

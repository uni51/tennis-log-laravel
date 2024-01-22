<?php

namespace App\Rules;

use App\Models\Memo;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ValidMemoOwner implements Rule
{
    protected int $userId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return Memo::where('id', $value)->where('user_id', $this->userId)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return '指定されたIDのメモが見つからないか、あなたがそのオーナーではありません。';
    }
}

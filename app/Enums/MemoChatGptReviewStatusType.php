<?php
declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class MemoChatGptReviewStatusType extends Enum {
    const NOT_REVIEWED = 0; // 未審査
    const PASSED_CHAT_GPT_REVIEW = 1; // ChatGPTによる審査通過
    const NG_CHAT_GPT_REVIEW = 2; // ChatGPTによる審査NG
    const VERIFIED_BY_ADMIN = 3; // 管理者での審査済


    // ここから先を追加
    public static function getDescription($value): string
    {
        if ($value === self::NOT_REVIEWED) {
            return '未審査';
        }
        if ($value === self::PASSED_CHAT_GPT_REVIEW) {
            return '審査通過';
        }
        if ($value === self::NG_CHAT_GPT_REVIEW) {
            return '審査NG';
        }
        if ($value === self::VERIFIED_BY_ADMIN) {
            return '管理者での審査済';
        }
        return parent::getDescription($value);
    }

    public static function getValue(string $key): int
    {
        if ($key === '未審査') {
            return self::NOT_REVIEWED;
        }
        if ($key === '審査通過') {
            return self::PASSED_CHAT_GPT_REVIEW;
        }
        if ($key === '審査NG') {
            return self::NG_CHAT_GPT_REVIEW;
        }
        if ($key === '管理者での審査済') {
            return self::VERIFIED_BY_ADMIN;
        }
        return parent::getValue($key);
    }
}

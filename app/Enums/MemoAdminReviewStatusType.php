<?php
declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class MemoAdminReviewStatusType extends Enum {
    const NOT_REVIEWED = 0; // 未審査
    const REVIEW_REQUIRED = 1; // 審査必要
    const WAITING_FOR_FIX = 2; // 修正待ち（管理者による審査NG、掲載一時停止）
    const PASSED_ADMIN_REVIEW = 3; // 管理者による審査通過


    // ここから先を追加
    public static function getDescription($value): string
    {
        if ($value === self::NOT_REVIEWED) {
            return '未審査';
        }
        if ($value === self::REVIEW_REQUIRED) {
            return '審査必要';
        }
        if ($value === self::WAITING_FOR_FIX) {
            return '修正待ち';
        }
        if ($value === self::PASSED_ADMIN_REVIEW) {
            return '審査通過';
        }
        return parent::getDescription($value);
    }

    public static function getValue(string $key): int
    {
        if ($key === '未審査') {
            return self::NOT_REVIEWED;
        }
        if ($key === '審査必要') {
            return self::REVIEW_REQUIRED;
        }
        if ($key === '修正待ち') {
            return self::WAITING_FOR_FIX;
        }
        if ($key === '審査通過') {
            return self::PASSED_ADMIN_REVIEW;
        }
        return parent::getValue($key);
    }
}

<?php
declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class MemoAdminReviewStatusType extends Enum {
    const NOT_REVIEWED = 0; // 未審査
    const REVIEW_REQUIRED = 1; // 審査必要
    const FIX_REQUIRED = 2; // 修正依頼中（審査NG）
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
        if ($value === self::FIX_REQUIRED) {
            return '修正依頼中';
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
        if ($key === '修正依頼中') {
            return self::FIX_REQUIRED;
        }
        if ($key === '審査通過') {
            return self::PASSED_ADMIN_REVIEW;
        }
        return parent::getValue($key);
    }
}

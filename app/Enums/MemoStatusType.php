<?php
declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class MemoStatusType extends Enum {
    const DRAFT = 0; // 下書き
    const PUBLISHING = 1; // 公開中
    const SHARING = 2; // シェア
    const UN_PUBLISHING = 3; // 非公開

    const DRAFT_LABEL = '下書き';
    const PUBLISHING_LABEL = '公開中';
    const SHARING_LABEL = 'シェア';
    const UN_PUBLISHING_LABEL = '非公開';

// ここから先を追加
    public static function getDescription($value): string
    {
        if ($value === self::DRAFT) {
            return self::DRAFT_LABEL;
        }
        if ($value === self::PUBLISHING) {
            return self::PUBLISHING_LABEL;
        }
        if ($value === self::SHARING) {
            return self::SHARING_LABEL;
        }
        if ($value === self::UN_PUBLISHING) {
            return self::UN_PUBLISHING_LABEL;
        }
        return parent::getDescription($value);
    }

    public static function getValue(string $key): int
    {
        if ($key === self::DRAFT_LABEL) {
            return self::DRAFT;
        }
        if ($key === self::PUBLISHING_LABEL) {
            return self::PUBLISHING;
        }
        if ($key === self::SHARING_LABEL) {
            return self::SHARING;
        }
        if ($key === self::UN_PUBLISHING_LABEL) {
            return self::UN_PUBLISHING;
        }
        return parent::getValue($key);
    }
}

<?php
declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class DominantHandType extends Enum {
    const RIGHT_HANDED = 0; // 右利き
    const LEFT_HANDED = 1; // 左利き
    const BOTH_HANDED = 2; // 利き手なし（左右同じくらい）

// ここから先を追加
    public static function getDescription($value): string
    {
        if ($value === self::RIGHT_HANDED) {
            return '右利き';
        }
        if ($value === self::LEFT_HANDED) {
            return '左利き';
        }
        if ($value === self::BOTH_HANDED) {
            return '利き手なし（左右同じくらい）';
        }
        return parent::getDescription($value);
    }

    public static function getValue(string $key): int
    {
        if ($key === '右利き') {
            return self::RIGHT_HANDED;
        }
        if ($key === '左利き') {
            return self::LEFT_HANDED;
        }
        if ($key === '利き手なし（左右同じくらい）') {
            return self::BOTH_HANDED;
        }
        return parent::getValue($key);
    }
}

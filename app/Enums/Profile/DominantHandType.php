<?php

declare(strict_types=1);

namespace App\Enums\Profile;

use BenSampo\Enum\Enum;

final class DominantHandType extends Enum
{
    const UNSELECTED = 0; // 選択してください
    const RIGHT_HANDED = 1; // 右利き
    const LEFT_HANDED = 2; // 左利き
    const BOTH_HANDED = 3; // 両利き

    private static $descriptions = [
        self::UNSELECTED => '選択してください',
        self::RIGHT_HANDED => '右利き',
        self::LEFT_HANDED => '左利き',
        self::BOTH_HANDED => '両利き',
    ];

    public static function getDescription($value): string
    {
        return self::$descriptions[$value] ?? parent::getDescription($value);
    }

    private static $values = [
        '選択してください' => self::UNSELECTED,
        '右利き' => self::RIGHT_HANDED,
        '左利き' => self::LEFT_HANDED,
        '両利き' => self::BOTH_HANDED,
    ];

    public static function getValue(string $key): int
    {
        return self::$values[$key] ?? parent::getValue($key);
    }
}

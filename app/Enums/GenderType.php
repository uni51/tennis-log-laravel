<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class GenderType extends Enum
{
    const UNSELECTED = 0; // 選択してください
    const MALE = 1; // 男性
    const FEMALE = 2; // 女性
    // const OTHER = 3; // その他

    private static $descriptions = [
        self::UNSELECTED => '選択してください',
        self::MALE => '男性',
        self::FEMALE => '女性',
        // self::OTHER => 'その他',
    ];

    public static function getDescription($value): string
    {
        return self::$descriptions[$value] ?? parent::getDescription($value);
    }

    private static $values = [
        '選択してください' => self::UNSELECTED,
        '男性' => self::MALE,
        '女性' => self::FEMALE,
        // 'その他' => self::OTHER,
    ];

    public static function getValue(string $key): int
    {
        return self::$values[$key] ?? parent::getValue($key);
    }
}

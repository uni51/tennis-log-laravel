<?php

declare(strict_types=1);

namespace App\Enums\Profile;

use BenSampo\Enum\Enum;

final class TennisLevelType extends Enum
{
    const UNSELECTED = 0; // 選択してください
    const BEGINNER = 1; // 初心者
    const ELEMENTARY_LEVEL = 2; // 初級
    const BEGINNER_INTERMEDIATE = 3; // 初中級
    const INTERMEDIATE = 4; // 中級
    const UPPER_INTERMEDIATE = 5; // 中上級
    const ADVANCED = 6; // 上級
    const SEMI_PROFESSIONAL = 7; // セミプロ
    const PROFESSIONAL = 8; // プロ

    private static $descriptions = [
        self::UNSELECTED => '選択してください',
        self::BEGINNER => '初心者',
        self::ELEMENTARY_LEVEL => '初級',
        self::BEGINNER_INTERMEDIATE => '初中級',
        self::INTERMEDIATE => '中級',
        self::UPPER_INTERMEDIATE => '中上級',
        self::ADVANCED => '上級',
        self::SEMI_PROFESSIONAL => 'セミプロ',
        self::PROFESSIONAL => 'プロ',
    ];

    public static function getDescription($value): string
    {
        return self::$descriptions[$value] ?? parent::getDescription($value);
    }

    private static $values = [
        '選択してください' => self::UNSELECTED,
        '初心者' => self::BEGINNER,
        '初級' => self::ELEMENTARY_LEVEL,
        '初中級' => self::BEGINNER_INTERMEDIATE,
        '中級' => self::INTERMEDIATE,
        '中上級' => self::UPPER_INTERMEDIATE,
        '上級' => self::ADVANCED,
        'セミプロ' => self::SEMI_PROFESSIONAL,
        'プロ' => self::PROFESSIONAL,
    ];

    public static function getValue(string $key): int
    {
        return self::$values[$key] ?? parent::getValue($key);
    }
}

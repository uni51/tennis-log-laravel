<?php

declare(strict_types=1);

namespace App\Enums\Profile;

use BenSampo\Enum\Enum;

final class PlayFrequencyType extends Enum
{
    const UNSELECTED = 0; // 選択してください
    const LESS_THAN_ONCE_A_MONTH = 1; // 月に1回未満
    const ONCE_A_MONTH = 2; // 月1回程度
    const ONCE_EVERY_TWO_WEEKS = 3; // 2週間に1回程度ほど
    const ONCE_A_WEEK = 4; // 週1回程度
    const TWICE_A_WEEK = 5; // 週2回程度
    const THREE_FOUR_TIMES_A_WEEK  = 6; // 週3〜4回程度
    const FIVE_OR_MORE_TIMES_A_WEEK  = 7; // 週5回以上

    private static $descriptions = [
        self::UNSELECTED => '選択してください',
        self::LESS_THAN_ONCE_A_MONTH => '月に1回未満',
        self::ONCE_A_MONTH => '月1回程度',
        self::ONCE_EVERY_TWO_WEEKS => '2週間に1回程度',
        self::ONCE_A_WEEK => '週1回程度',
        self::TWICE_A_WEEK => '週2回程度',
        self::THREE_FOUR_TIMES_A_WEEK => '週3〜4回程度',
        self::FIVE_OR_MORE_TIMES_A_WEEK => '週5回以上',
    ];

    public static function getDescription($value): string
    {
        return self::$descriptions[$value] ?? parent::getDescription($value);
    }

    private static $values = [
        '選択してください' => self::UNSELECTED,
        '月に1回未満' => self::LESS_THAN_ONCE_A_MONTH,
        '月1回程度' => self::ONCE_A_MONTH,
        '2週間に1回程度' => self::ONCE_EVERY_TWO_WEEKS,
        '週1回程度' => self::ONCE_A_WEEK,
        '週2回程度' => self::TWICE_A_WEEK,
        '週3〜4回程度' => self::THREE_FOUR_TIMES_A_WEEK,
        '週5回以上' => self::FIVE_OR_MORE_TIMES_A_WEEK,
    ];

    public static function getValue(string $key): int
    {
        return self::$values[$key] ?? parent::getValue($key);
    }
}

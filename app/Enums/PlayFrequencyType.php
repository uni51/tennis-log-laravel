<?php
declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PlayFrequencyType extends Enum {
    const LESS_THAN_ONCE_A_MONTH = 0; // 月に1回程度
    const ONCE_EVERY_TWO_WEEKS = 1; // 2週間に1回程度ほど
    const ONCE_A_WEEK = 2; // 週1回程度
    const TWICE_A_WEEK = 3; // 週2回程度
    const THREE_FOUR_TIMES_A_WEEK  = 4; // 週3〜4回程度
    const FIVE_OR_MORE_TIMES_A_WEEK  = 5; // 週5回以上

// ここから先を追加
    public static function getDescription($value): string
    {
        if ($value === self::LESS_THAN_ONCE_A_MONTH) {
            return '月に1回程度';
        }
        if ($value === self::ONCE_EVERY_TWO_WEEKS) {
            return '2週間に1回程度';
        }
        if ($value === self::ONCE_A_WEEK) {
            return '週1回程度';
        }
        if ($value === self::TWICE_A_WEEK) {
            return '週2回程度';
        }
        if ($value === self::THREE_FOUR_TIMES_A_WEEK ) {
            return '週3〜4回程度';
        }
        if ($value === self::FIVE_OR_MORE_TIMES_A_WEEK) {
            return '週5回以上';
        }
        return parent::getDescription($value);
    }

    public static function getValue(string $key): int
    {
        if ($key === '月に1回程度') {
            return self::LESS_THAN_ONCE_A_MONTH;
        }
        if ($key === '2週間に1回程度') {
            return self::ONCE_EVERY_TWO_WEEKS;
        }
        if ($key === '週1回程度') {
            return self::ONCE_A_WEEK;
        }
        if ($key === '週2回程度') {
            return self::TWICE_A_WEEK;
        }
        if ($key === '週3〜4回程度') {
            return self::THREE_FOUR_TIMES_A_WEEK;
        }
        if ($key === '週5回以上') {
            return self::FIVE_OR_MORE_TIMES_A_WEEK;
        }
        return parent::getValue($key);
    }
}

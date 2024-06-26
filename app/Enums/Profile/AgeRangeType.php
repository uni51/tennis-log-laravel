<?php

declare(strict_types=1);

namespace App\Enums\Profile;

use BenSampo\Enum\Enum;

final class AgeRangeType extends Enum
{
    const UNSELECTED = 0; // 選択してください
    const KINDERGARTEN_AND_BELOW = 1; // 幼稚園以下
    const ELEMENTARY_SCHOOL = 2; // 小学生
    const MIDDLE_SCHOOL = 3; // 中学生
    const HIGH_SCHOOL = 4; // 高校生
    const COLLEGE_STUDENT = 5; // 大学生・専門学校生
    const BETWEEN_20_AND_24 = 6; // 20代前半（20歳～24歳）
    const BETWEEN_25_AND_29 = 7; // 20代後半（25歳～29歳）
    const BETWEEN_30_AND_34 = 8; // 30代前半（30歳～34歳）
    const BETWEEN_35_AND_39 = 9; // 30代後半（35歳～39歳）
    const BETWEEN_40_AND_44 = 10; // 40代前半（40歳～44歳）
    const BETWEEN_45_AND_49 = 11; // 40代後半（45歳～49歳）
    const BETWEEN_50_AND_54 = 12; // 50代前半（50歳～54歳）
    const BETWEEN_55_AND_59 = 13; // 50代後半（55歳～59歳）
    const BETWEEN_60_AND_64 = 14; // 60代前半（60歳～64歳）
    const BETWEEN_65_AND_69 = 15; // 60代後半（65歳～69歳）
    const BETWEEN_70_AND_74 = 16; // 70代前半（70歳～74歳）
    const BETWEEN_75_AND_79 = 17; // 70代後半（75歳～79歳）
    const BETWEEN_80_AND_84 = 18; // 80代前半（80歳～84歳）

    const OVER_85 = 19; // 85歳以上

    private static $descriptions = [
        self::UNSELECTED => '選択してください',
        self::KINDERGARTEN_AND_BELOW => '幼稚園以下',
        self::ELEMENTARY_SCHOOL => '小学生',
        self::MIDDLE_SCHOOL => '中学生',
        self::HIGH_SCHOOL => '高校生',
        self::COLLEGE_STUDENT => '大学生・専門学校生',
        self::BETWEEN_20_AND_24 => '20代前半',
        self::BETWEEN_25_AND_29 => '20代後半',
        self::BETWEEN_30_AND_34 => '30代前半',
        self::BETWEEN_35_AND_39 => '30代後半',
        self::BETWEEN_40_AND_44 => '40代前半',
        self::BETWEEN_45_AND_49 => '40代後半',
        self::BETWEEN_50_AND_54 => '50代前半',
        self::BETWEEN_55_AND_59 => '50代後半',
        self::BETWEEN_60_AND_64 => '60代前半',
        self::BETWEEN_65_AND_69 => '60代後半',
        self::BETWEEN_70_AND_74 => '70代前半',
        self::BETWEEN_75_AND_79 => '70代後半',
        self::BETWEEN_80_AND_84 => '80代前半',
        self::OVER_85 => '85歳以上',
    ];

    public static function getDescription($value): string
    {
        return self::$descriptions[$value] ?? parent::getDescription($value);
    }

    private static $values = [
        '選択してください' => self::UNSELECTED,
        '幼稚園以下' => self::KINDERGARTEN_AND_BELOW,
        '小学生' => self::ELEMENTARY_SCHOOL,
        '中学生' => self::MIDDLE_SCHOOL,
        '高校生' => self::HIGH_SCHOOL,
        '大学生・専門学校生' => self::COLLEGE_STUDENT,
        '20代前半' => self::BETWEEN_20_AND_24,
        '20代後半' => self::BETWEEN_25_AND_29,
        '30代前半' => self::BETWEEN_30_AND_34,
        '30代後半' => self::BETWEEN_35_AND_39,
        '40代前半' => self::BETWEEN_40_AND_44,
        '40代後半' => self::BETWEEN_45_AND_49,
        '50代前半' => self::BETWEEN_50_AND_54,
        '50代後半' => self::BETWEEN_55_AND_59,
        '60代前半' => self::BETWEEN_60_AND_64,
        '60代後半' => self::BETWEEN_65_AND_69,
        '70代前半' => self::BETWEEN_70_AND_74,
        '70代後半' => self::BETWEEN_75_AND_79,
        '80代前半' => self::BETWEEN_80_AND_84,
        '85歳以上' => self::OVER_85,
    ];

    public static function getValue(string $key): int
    {
        return self::$values[$key] ?? parent::getValue($key);
    }
}

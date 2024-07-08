<?php

declare(strict_types=1);

namespace App\Enums\Profile;

use BenSampo\Enum\Enum;

final class CareerType extends Enum
{
    const UNSELECTED = 0; // 選択してください
    const NO_EXPERIENCE = 1; // ほぼ経験なし
    const THREE_MONTHS_OR_LESS = 2; // 0～3ヶ月
    const LESS_THAN_HALF_A_YEAR = 3; // 4～6ヶ月
    const ONE_YEAR_OR_LESS = 4; // 7～12ヶ月
    const ONE_YEAR = 5; // 1年
    const TWO_YEARS = 6; // 2年
    const THREE_YEARS = 7; // 3年
    const FOUR_YEARS = 8; // 4年
    const FIVE_YEARS = 9; // 5年
    const SEVEN_YEARS_OR_LESS = 10; // 6年～7年
    const NINE_YEARS_OR_LESS = 11; // 8年～9年
    const OVER_TEN_YEARS = 12; // 10年以上
    const OVER_FIFTEEN_YEARS = 13; // 15年以上
    const OVER_TWENTY_YEARS = 14; // 20年以上
    const OVER_THIRTY_YEARS = 15; // 30年以上

    private static $descriptions = [
        self::UNSELECTED => '選択してください',
        self::NO_EXPERIENCE => 'ほぼ経験なし',
        self::THREE_MONTHS_OR_LESS => '0～3ヶ月',
        self::LESS_THAN_HALF_A_YEAR => '4～6ヶ月',
        self::ONE_YEAR_OR_LESS => '7～12ヶ月',
        self::ONE_YEAR => '1年',
        self::TWO_YEARS => '2年',
        self::THREE_YEARS => '3年',
        self::FOUR_YEARS => '4年',
        self::FIVE_YEARS => '5年',
        self::SEVEN_YEARS_OR_LESS => '6年～7年',
        self::NINE_YEARS_OR_LESS => '8年～9年',
        self::OVER_TEN_YEARS => '10年以上',
        self::OVER_FIFTEEN_YEARS => '15年以上',
        self::OVER_TWENTY_YEARS => '20年以上',
        self::OVER_THIRTY_YEARS => '30年以上',
    ];

    public static function getDescription($value): string
    {
        return self::$descriptions[$value] ?? parent::getDescription($value);
    }

    private static $values = [
        '選択してください' => self::UNSELECTED,
        'ほぼ経験なし' => self::NO_EXPERIENCE,
        '0～3ヶ月' => self::THREE_MONTHS_OR_LESS,
        '4～6ヶ月' => self::LESS_THAN_HALF_A_YEAR,
        '7～12ヶ月' => self::ONE_YEAR_OR_LESS,
        '1年' => self::ONE_YEAR,
        '2年' => self::TWO_YEARS,
        '3年' => self::THREE_YEARS,
        '4年' => self::FOUR_YEARS,
        '5年' => self::FIVE_YEARS,
        '6年～7年' => self::SEVEN_YEARS_OR_LESS,
        '8年～9年' => self::NINE_YEARS_OR_LESS,
        '10年以上' => self::OVER_TEN_YEARS,
        '15年以上' => self::OVER_FIFTEEN_YEARS,
        '20年以上' => self::OVER_TWENTY_YEARS,
        '30年以上' => self::OVER_THIRTY_YEARS,
    ];

    public static function getValue(string $key): int
    {
        return self::$values[$key] ?? parent::getValue($key);
    }
}

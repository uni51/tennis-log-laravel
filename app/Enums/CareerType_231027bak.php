<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CareerType_231027bak extends Enum
{
    const NO_EXPERIENCE = 0; // ほぼ経験なし
    const THREE_MONTHS_OR_LESS = 1; // 0～3ヶ月
    const LESS_THAN_HALF_A_YEAR = 2; // 4～6ヶ月
    const ONE_YEAR_OR_LESS = 3; // 7～12ヶ月
    const THREE_YEARS_OR_LESS = 4; // 1年～3年
    const SIX_YEARS_OR_LESS = 5; // 4年～6年
    const TEN_YEARS_OR_LESS = 6; // 7年～9年
    const OVER_TEN_YEARS = 7; // 10年以上
    const OVER_TWENTY_YEARS = 8; // 20年以上
    const OVER_THIRTY_YEARS = 9; // 30年以上

// ここから先を追加
    public static function getDescription($value): string
    {
        if ($value === self::NO_EXPERIENCE) {
            return 'ほぼ経験なし';
        }
        if ($value === self::THREE_MONTHS_OR_LESS) {
            return '0～3ヶ月';
        }
        if ($value === self::LESS_THAN_HALF_A_YEAR) {
            return '4～6ヶ月';
        }
        if ($value === self::ONE_YEAR_OR_LESS) {
            return '7～12ヶ月';
        }
        if ($value === self::THREE_YEARS_OR_LESS) {
            return '1年～3年';
        }
        if ($value === self::SIX_YEARS_OR_LESS) {
            return '4年～6年';
        }
        if ($value === self::TEN_YEARS_OR_LESS) {
            return '7年～9年';
        }
        if ($value === self::OVER_TEN_YEARS) {
            return '10年以上';
        }
        if ($value === self::OVER_TWENTY_YEARS) {
            return '20年以上';
        }
        if ($value === self::OVER_THIRTY_YEARS) {
            return '30年以上';
        }

        return parent::getDescription($value);
    }

    public static function getValue(string $key): int
    {
        if ($key === 'ほぼ経験なし') {
            return self::NO_EXPERIENCE;
        }
        if ($key === '0～3ヶ月') {
            return self::THREE_MONTHS_OR_LESS;
        }
        if ($key === '4～6ヶ月') {
            return self::LESS_THAN_HALF_A_YEAR;
        }
        if ($key === '7～12ヶ月') {
            return self::ONE_YEAR_OR_LESS;
        }
        if ($key === '1年～3年') {
            return self::THREE_YEARS_OR_LESS;
        }
        if ($key === '4年～6年') {
            return self::SIX_YEARS_OR_LESS;
        }
        if ($key === '7年～9年') {
            return self::TEN_YEARS_OR_LESS;
        }
        if ($key === '10年以上') {
            return self::OVER_TEN_YEARS;
        }
        if ($key === '20年以上') {
            return self::OVER_TWENTY_YEARS;
        }
        if ($key === '30年以上') {
            return self::OVER_THIRTY_YEARS;
        }

        return parent::getValue($key);
    }
}

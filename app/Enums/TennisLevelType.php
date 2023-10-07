<?php
declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TennisLevelType extends Enum {
    const BEGINNER = 0; // 初心者
    const ELEMENTARY_LEVEL = 1; // 初級
    const BEGINNER_INTERMEDIATE = 2; // 初中級
    const INTERMEDIATE = 3; // 初中級
    const UPPER_INTERMEDIATE = 4; // 中上級
    const ADVANCED = 5; // 上級
    const SEMI_PROFESSIONAL = 6; // セミプロ
    const PROFESSIONAL = 7; // セミプロ

// ここから先を追加
    public static function getDescription($value): string
    {
        if ($value === self::BEGINNER) {
            return '初心者';
        }
        if ($value === self::ELEMENTARY_LEVEL) {
            return '初級';
        }
        if ($value === self::BEGINNER_INTERMEDIATE) {
            return '初中級';
        }
        if ($value === self::INTERMEDIATE) {
            return '中級';
        }
        if ($value === self::UPPER_INTERMEDIATE) {
            return '中上級';
        }
        if ($value === self::ADVANCED) {
            return '上級';
        }
        if ($value === self::SEMI_PROFESSIONAL) {
            return 'セミプロ';
        }
        if ($value === self::PROFESSIONAL) {
            return 'プロ';
        }

        return parent::getDescription($value);
    }

    public static function getValue(string $key): int
    {
        if ($key === '初心者') {
            return self::BEGINNER;
        }
        if ($key === '初級') {
            return self::ELEMENTARY_LEVEL;
        }
        if ($key === '初中級') {
            return self::BEGINNER_INTERMEDIATE;
        }
        if ($key === '中級') {
            return self::INTERMEDIATE;
        }
        if ($key === '中上級') {
            return self::UPPER_INTERMEDIATE;
        }
        if ($key === '上級') {
            return self::ADVANCED;
        }
        if ($key === 'セミプロ') {
            return self::SEMI_PROFESSIONAL;
        }
        if ($key === 'プロ') {
            return self::PROFESSIONAL;
        }

        return parent::getValue($key);
    }
}

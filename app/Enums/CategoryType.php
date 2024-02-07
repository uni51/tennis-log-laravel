<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CategoryType extends Enum
{
    const FOREHAND = 1; // フォアハンド
    const DOUBLE_BACKHAND = 2; // 両手バックハンド
    const SINGLE_BACKHAND = 3; // 片手バックハンド
    const SERVE = 4; // サーブ
    const RETURN = 5; // リターン
    const VOLLEY = 6; // ボレー
    const SMASH = 7; // スマッシュ
    const SINGLES = 8; // シングルス
    const DOUBLES = 9; // ダブルス
    const GEAR = 10; // ギア
    const OTHER = 99; // その他

    private static $descriptions = [
        self::FOREHAND => 'フォアハンド',
        self::DOUBLE_BACKHAND => '両手バックハンド',
        self::SINGLE_BACKHAND => '片手バックハンド',
        self::SERVE => 'サーブ',
        self::RETURN => 'リターン',
        self::VOLLEY => 'ボレー',
        self::SMASH => 'スマッシュ',
        self::SINGLES => 'シングルス',
        self::DOUBLES => 'ダブルス',
        self::GEAR => 'ギア',
        self::OTHER => 'その他',
    ];

    public static function getDescription($value): string
    {
        return self::$descriptions[$value] ?? parent::getDescription($value);
    }

    private static $values = [
        'フォアハンド' => self::FOREHAND,
        '両手バックハンド' => self::DOUBLE_BACKHAND,
        '片手バックハンド' => self::SINGLE_BACKHAND,
        'サーブ' => self::SERVE,
        'リターン' => self::RETURN,
        'ボレー' => self::VOLLEY,
        'スマッシュ' => self::SMASH,
        'シングルス' => self::SINGLES,
        'ダブルス' => self::DOUBLES,
        'ギア' => self::GEAR,
        'その他' => self::OTHER,
    ];

    public static function getValue(string $key): int
    {
        return self::$values[$key] ?? parent::getValue($key);
    }
}

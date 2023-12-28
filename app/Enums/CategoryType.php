<?php
declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use Illuminate\Support\Facades\Log;

final class CategoryType extends Enum
{
    const FOREHAND = 1; // フォアハンド
    const BACKHAND = 2; // バックハンド
    const SERVE = 3; // サーブ
    const RETURN = 4; // リターン
    const VOLLEY = 5; // ボレー
    const SMASH = 6; // スマッシュ
    const SINGLES = 7; // シングルス
    const DOUBLES = 8; // ダブルス
    const OTHER = 9; // その他

    private const DESCRIPTIONS = [
        self::FOREHAND => 'フォアハンド',
        self::BACKHAND => 'バックハンド',
        self::SERVE => 'サーブ',
        self::RETURN => 'リターン',
        self::VOLLEY => 'ボレー',
        self::SMASH => 'スマッシュ',
        self::SINGLES => 'シングルス',
        self::DOUBLES => 'ダブルス',
        self::OTHER => 'その他',
    ];

    public static function getDescription($value): string
    {
        // $value が整数でない場合にエラーログを出力
        if (!is_int($value)) {
            Log::error('Invalid $value type in getDescription', ['value' => $value]);
            return 'Unknown Category';
        }

        return self::DESCRIPTIONS[$value] ?? parent::getDescription($value);
    }

    private const VALUES = [
        'フォアハンド' => self::FOREHAND,
        'バックハンド' => self::BACKHAND,
        'サーブ' => self::SERVE,
        'リターン' => self::RETURN,
        'ボレー' => self::VOLLEY,
        'スマッシュ' => self::SMASH,
        'シングルス' => self::SINGLES,
        'ダブルス' => self::DOUBLES,
        'その他' => self::OTHER,
    ];

    public static function getValue(string $key): int
    {
        return self::VALUES[$key] ?? parent::getValue($key);
    }
}

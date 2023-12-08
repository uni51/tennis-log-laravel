<?php
declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CategoryType extends Enum {
    const FOREHAND = 1; // フォアハンド
    const BACKHAND = 2; // バックハンド
    const SERVE = 3; // サーブ
    const RETURN = 4; // リターン
    const VOLLEY = 5; // ボレー
    const SMASH = 6; // スマッシュ
    const GAME = 7; // ゲーム
    const OTHER = 99; // その他

// ここから先を追加
    public static function getDescription($value): string
    {
        if ($value === self::FOREHAND) {
            return 'フォアハンド';
        }
        if ($value === self::BACKHAND) {
            return 'バックハンド';
        }
        if ($value === self::SERVE) {
            return 'サーブ';
        }
        if ($value === self::RETURN) {
            return 'リターン';
        }
        if ($value === self::VOLLEY) {
            return 'ボレー';
        }
        if ($value === self::SMASH) {
            return 'スマッシュ';
        }
        if ($value === self::GAME) {
            return 'ゲーム';
        }
        if ($value === self::OTHER) {
            return 'その他';
        }
        return parent::getDescription($value);
    }

    public static function getValue(string $key): int
    {
        if ($key === 'フォアハンド') {
            return self::FOREHAND;
        }
        if ($key === 'バックハンド') {
            return self::BACKHAND;
        }
        if ($key === 'サーブ') {
            return self::SERVE;
        }
        if ($key === 'リターン') {
            return self::RETURN;
        }
        if ($key === 'ボレー') {
            return self::VOLLEY;
        }
        if ($key === 'スマッシュ') {
            return self::SMASH;
        }
        if ($key === 'ゲーム') {
            return self::GAME;
        }
        if ($key === 'その他') {
            return self::OTHER;
        }
        return parent::getValue($key);
    }
}

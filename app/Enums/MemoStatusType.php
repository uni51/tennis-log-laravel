<?php
declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class MemoStatusType extends Enum {
    const DRAFT = 0; // 下書き
    const PUBLISHING = 1; // 公開中
    const SHARING = 2; // シェア
    const UN_PUBLISHING = 3; // 非公開（自分だけが閲覧可能）
    const WAITING_FOR_FIX = 4; // 修正待ち（掲載一時停止中）


    // ここから先を追加
    public static function getDescription($value): string
    {
        if ($value === self::DRAFT) {
            return '下書き';
        }
        if ($value === self::PUBLISHING) {
            return '公開中';
        }
        if ($value === self::SHARING) {
            return 'シェア';
        }
        if ($value === self::UN_PUBLISHING) {
            return '非公開';
        }
        if ($value === self::WAITING_FOR_FIX) {
            return '修正待ち（掲載一時停止中）';
        }
        return parent::getDescription($value);
    }

    public static function getValue(string $key): int
    {
        if ($key === '下書き') {
            return self::DRAFT;
        }
        if ($key === '公開中') {
            return self::PUBLISHING;
        }
        if ($key === 'シェア') {
            return self::SHARING;
        }
        if ($key === '非公開') {
            return self::UN_PUBLISHING;
        }
        if ($key === '修正待ち（掲載一時停止中）') {
            return self::WAITING_FOR_FIX;
        }
        return parent::getValue($key);
    }
}

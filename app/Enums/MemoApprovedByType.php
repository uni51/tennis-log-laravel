<?php
declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class MemoApprovedByType extends Enum {
    const NOT_APPROVED = 0; // 未承認
    const APPROVED_BY_CHAT_GPT = 1; // ChatGPTによる承認
    const APPROVED_BY_ADMIN = 2; // 管理者による承認


    // ここから先を追加
    public static function getDescription($value): string
    {
        if ($value === self::NOT_APPROVED) {
            return '未承認';
        }
        if ($value === self::APPROVED_BY_CHAT_GPT) {
            return 'ChatGPTによる承認';
        }
        if ($value === self::APPROVED_BY_ADMIN) {
            return '管理者による承認';
        }

        return parent::getDescription($value);
    }

    public static function getValue(string $key): int
    {
        if ($key === '未承認') {
            return self::NOT_APPROVED;
        }
        if ($key === 'ChatGPTによる承認') {
            return self::APPROVED_BY_CHAT_GPT;
        }
        if ($key === '管理者による承認') {
            return self::APPROVED_BY_ADMIN;
        }

        return parent::getValue($key);
    }
}

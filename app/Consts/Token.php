<?php
declare(strict_types=1);

namespace App\Consts;

class Token {
    // Tokenの期限を50分に設定
    const TokenValidMinutes = 30;
//     const TokenValidMinutes = 2;  // 検証時用

    // Cookieの有効期限を52分に設定（Cookieの有効期限は、Tokenの期限よりも長く設定する必要がある）
    const CookieTokenValidMinutes = 32;
//     const CookieTokenValidMinutes = 3; // 検証時用

    // Tokenのチェックを開始する時間を、Tokenの有効期限-2分 で設定
    const WaitingUntilCheckMinutes = 28;
//     const WaitingUntilCheckMinutes = 1; // 検証時用

    // キャッシュの有効秒数（WaitingUntilCheckMinutes x 60 になる）
    const CacheValidSeconds = self::WaitingUntilCheckMinutes * 60;
}

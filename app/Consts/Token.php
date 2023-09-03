<?php
declare(strict_types=1);

namespace App\Consts;

class Token {
    // Tokenの期限を30分に設定
    const TokenValidMinutes = 15;
//     const TokenValidMinutes = 2;  // 検証時用

    // Cookieの有効期限を60分（1時間）に設定（Cookieの有効期限は、Tokenの期限よりも長く設定する必要がある）
    // この時間内であれば、フロント側から自動ログアウト・自動ログインが有効になるので、入力中の内容が破棄されないで保たれる
    const CookieTokenValidMinutes = 20;
//     const CookieTokenValidMinutes = 5; // 検証時用

    // Tokenのチェックを開始する時間を、Tokenの有効期限-2分 で設定
    const WaitingUntilCheckMinutes = 13;
//     const WaitingUntilCheckMinutes = 1; // 検証時用

    // キャッシュの有効秒数（WaitingUntilCheckMinutes x 60 になる）
    const CacheValidSeconds = self::WaitingUntilCheckMinutes * 60;
}

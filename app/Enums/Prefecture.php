<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Prefecture extends Enum
{
    const HOKKAIDO = 1; // 北海道
    const AOMORI = 2; // 青森県
    const IWATE = 3; // 岩手県
    const MIYAGI = 4; // 宮城県
    const AKITA = 5; // 秋田県
    const YAMAGATA = 6; // 山形県
    const FUKUSHIMA = 7; // 福島県
    const IBARAKI = 8; // 茨城県
    const TOCHIGI = 9; // 栃木県
    const GUNMA = 10; // 群馬県
    const SAITAMA = 11; // 埼玉県
    const CHIBA = 12; // 千葉県
    const TOKYO = 13; // 東京都
    const KANAGAWA = 14; // 神奈川県
    const NIIGATA = 15; // 新潟県
    const TOYAMA = 16; // 富山県
    const ISHIKAWA = 17; // 石川県
    const FUKUI = 18; // 福井県
    const YAMANASHI = 19; // 山梨県
    const NAGANO = 20; // 長野県
    const GIFU = 21; // 岐阜県
    const SHIZUOKA = 22; // 静岡県
    const AICHI = 23; // 愛知県
    const MIE = 24; // 三重県
    const SHIGA = 25; // 滋賀県
    const KYOTO = 26; // 京都府
    const OSAKA = 27; // 大阪府
    const HYOGO = 28; // 兵庫県
    const NARA = 29; // 奈良県
    const WAKAYAMA = 30; // 和歌山県
    const TOTTORI = 31; // 鳥取県
    const SHIMANE = 32; // 島根県
    const OKAYAMA = 33; // 岡山県
    const HIROSHIMA = 34; // 広島県
    const YAMAGUCHI = 35; // 山口県
    const TOKUSHIMA = 36; // 徳島県
    const KAGAWA = 37; // 香川県
    const EHIME = 38; // 愛媛県
    const KOCHI = 39; // 高知県
    const FUKUOKA = 40; // 福岡県
    const SAGA = 41; // 佐賀県
    const NAGASAKI = 42; // 長崎県
    const KUMAMOTO = 43; // 熊本県
    const OITA = 44; // 大分県
    const MIYAZAKI = 45; // 宮崎県
    const KAGOSHIMA = 46; // 鹿児島県
    const OKINAWA = 47; // 沖縄県

    private static $descriptions = [
        self::HOKKAIDO => '北海道',
        self::AOMORI => '青森県',
        self::IWATE => '岩手県',
        self::MIYAGI => '宮城県',
        self::AKITA => '秋田県',
        self::YAMAGATA => '山形県',
        self::FUKUSHIMA => '福島県',
        self::IBARAKI => '茨城県',
        self::TOCHIGI => '栃木県',
        self::GUNMA => '群馬県',
        self::SAITAMA => '埼玉県',
        self::CHIBA => '千葉県',
        self::TOKYO => '東京都',
        self::KANAGAWA => '神奈川県',
        self::NIIGATA => '新潟県',
        self::TOYAMA => '富山県',
        self::ISHIKAWA => '石川県',
        self::FUKUI => '福井県',
        self::YAMANASHI => '山梨県',
        self::NAGANO => '長野県',
        self::GIFU => '岐阜県',
        self::SHIZUOKA => '静岡県',
        self::AICHI => '愛知県',
        self::MIE => '三重県',
        self::SHIGA => '滋賀県',
        self::KYOTO => '京都府',
        self::OSAKA => '大阪府',
        self::HYOGO => '兵庫県',
        self::NARA => '奈良県',
        self::WAKAYAMA => '和歌山県',
        self::TOTTORI => '鳥取県',
        self::SHIMANE => '島根県',
        self::OKAYAMA => '岡山県',
        self::HIROSHIMA => '広島県',
        self::YAMAGUCHI => '山口県',
        self::TOKUSHIMA => '徳島県',
        self::KAGAWA => '香川県',
        self::EHIME => '愛媛県',
        self::KOCHI => '高知県',
        self::FUKUOKA => '福岡県',
        self::SAGA => '佐賀県',
        self::NAGASAKI => '長崎県',
        self::KUMAMOTO => '熊本県',
        self::OITA => '大分県',
        self::MIYAZAKI => '宮崎県',
        self::KAGOSHIMA => '鹿児島県',
        self::OKINAWA => '沖縄県',
    ];

    public static function getDescription($value): string
    {
        return self::$descriptions[$value] ?? parent::getDescription($value);
    }

    private static $values = [
        '北海道' => self::HOKKAIDO,
        '青森県' => self::AOMORI,
        '岩手県' => self::IWATE,
        '宮城県' => self::MIYAGI,
        '秋田県' => self::AKITA,
        '山形県' => self::YAMAGATA,
        '福島県' => self::FUKUSHIMA,
        '茨城県' => self::IBARAKI,
        '栃木県' => self::TOCHIGI,
        '群馬県' => self::GUNMA,
        '埼玉県' => self::SAITAMA,
        '千葉県' => self::CHIBA,
        '東京都' => self::TOKYO,
        '神奈川県' => self::KANAGAWA,
        '新潟県' => self::NIIGATA,
        '富山県' => self::TOYAMA,
        '石川県' => self::ISHIKAWA,
        '福井県' => self::FUKUI,
        '山梨県' => self::YAMANASHI,
        '長野県' => self::NAGANO,
        '岐阜県' => self::GIFU,
        '静岡県' => self::SHIZUOKA,
        '愛知県' => self::AICHI,
        '三重県' => self::MIE,
        '滋賀県' => self::SHIGA,
        '京都府' => self::KYOTO,
        '大阪府' => self::OSAKA,
        '兵庫県' => self::HYOGO,
        '奈良県' => self::NARA,
        '和歌山県' => self::WAKAYAMA,
        '鳥取県' => self::TOTTORI,
        '島根県' => self::SHIMANE,
        '岡山県' => self::OKAYAMA,
        '広島県' => self::HIROSHIMA,
        '山口県' => self::YAMAGUCHI,
        '徳島県' => self::TOKUSHIMA,
        '香川県' => self::KAGAWA,
        '愛媛県' => self::EHIME,
        '高知県' => self::KOCHI,
        '福岡県' => self::FUKUOKA,
        '佐賀県' => self::SAGA,
        '長崎県' => self::NAGASAKI,
        '熊本県' => self::KUMAMOTO,
        '大分県' => self::OITA,
        '宮崎県' => self::MIYAZAKI,
        '鹿児島県' => self::KAGOSHIMA,
        '沖縄県' => self::OKINAWA,
    ];

    public static function getValue(string $key): int
    {
        return self::$values[$key] ?? parent::getValue($key);
    }
}

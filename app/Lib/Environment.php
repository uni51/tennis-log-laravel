<?php

namespace App\Lib;

class Environment
{
    private const PRODUCTION  = 'production';
    private const STAGING     = 'staging';
    private const LOCAL       = 'local';

    public static function isProduction()
    {
        return config('app.env') == self::PRODUCTION;
    }

    public static function isStaging()
    {
        return config('app.env') == self::STAGING;
    }

    public static function isLocal()
    {
        return config('app.env') == self::LOCAL;
    }

    public static function getEnv(){
        return config('app.env');
    }
}

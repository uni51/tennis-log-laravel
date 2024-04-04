<?php

namespace App\Lib;

class DomainHelper
{
    private const LOCAL_DOMAIN = 'http://local-tennis-log.net:3000';

    public static function getDomain(): string
    {
        if (Environment::isLocal()) {
            return config('environment.domain.local', self::LOCAL_DOMAIN);
        }

        return '';
    }
}

<?php

namespace App\Lib;

class SendMailHelper
{
    public static function getAdminEmail(): string
    {
        return config('admin.email');
    }
}

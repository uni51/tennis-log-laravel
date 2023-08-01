<?php
declare(strict_types=1);

namespace App\Providers\Guards\Firebase;

final class VerifyIdTokenStatus
{
    const SUCCEED = 200;
    const EXPIRED = 401;
    const OTHER_FAILURE = 400;
}

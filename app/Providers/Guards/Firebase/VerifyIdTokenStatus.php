<?php
declare(strict_types=1);

namespace App\Providers\Guards\Firebase;

final class VerifyIdTokenStatus
{
    public int $status;

    const SUCCEED = 200;
    const EXPIRED = 401;
    const OTHER_FAILURE = 400;
    public function __construct(int $status)
    {
        $this->status = $status;
    }

    public function value()
    {
        return $this->status;
    }
}

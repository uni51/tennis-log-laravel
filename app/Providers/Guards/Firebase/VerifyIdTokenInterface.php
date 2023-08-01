<?php
declare(strict_types=1);

namespace App\Providers\Guards\Firebase;

interface VerifyIdTokenInterface
{
    public function verifyIdToken(string $token): VerifyTokenResponse;
}

<?php
declare(strict_types=1);

namespace App\Providers\Guards\Firebase;

use Kreait\Firebase\JWT\IdTokenVerifier;

final class LiveVerifyIdToken implements VerifyIdTokenInterface
{
    public function verifyIdToken(string $token): VerifyTokenResponse
    {
        try {
            /** @var IdTokenVerifier $client */
            $client = app()->make(IdTokenVerifier::class);
            $firebaseToken = $client->verifyIdToken($token);
            $payload = $firebaseToken->payload();
            return new VerifyTokenResponse(
                // VerifyIdTokenStatus::SUCCEED,
                new VerifyIdTokenStatus(VerifyIdTokenStatus::SUCCEED),
                (int)$payload['sub']
            );
        } catch (\Throwable $e) {
            if (strpos($e->getMessage(), 'The token is expired.') !== false) {
                return new VerifyTokenResponse(
                    // VerifyIdTokenStatus::EXPIRED,
                    new VerifyIdTokenStatus(VerifyIdTokenStatus::EXPIRED)
                );
            }
            return new VerifyTokenResponse(
                // VerifyIdTokenStatus::OTHER_FAILURE,
                new VerifyIdTokenStatus(VerifyIdTokenStatus::OTHER_FAILURE),
            );
        }
    }
}

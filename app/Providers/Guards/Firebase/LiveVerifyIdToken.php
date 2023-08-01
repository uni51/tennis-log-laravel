<?php
declare(strict_types=1);

namespace App\Providers\Guards\Firebase;

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
                VerifyIdTokenStatus::SUCCEED,
                (int)$payload['sub']
            );
        } catch (\Throwable $e) {
            if (strpos($e->getMessage(), 'The token is expired.') !== false) {
                return new VerifyTokenResponse(
                    VerifyIdTokenStatus::EXPIRED,
                );
            }
            return new VerifyTokenResponse(
                VerifyIdTokenStatus::OTHER_FAILURE,
            );
        }
    }
}

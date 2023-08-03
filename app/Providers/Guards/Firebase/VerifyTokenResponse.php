<?php
declare(strict_types=1);

namespace App\Providers\Guards\Firebase;

final class VerifyTokenResponse
{
//    private int $status;
    private VerifyIdTokenStatus $status;
    private ?int $userId;

    /**
     * VerifyTokenResponse constructor.
     * @param VerifyIdTokenStatus $status
     * @param int|null $userId
     */
//    public function __construct(int $status, ?int $userId = null)
    public function __construct(VerifyIdTokenStatus $status, ?int $userId = null)
    {
        $this->status = $status;
        $this->userId = $userId;
    }

    public function isOK(): bool
    {
        return $this->status->value() === VerifyIdTokenStatus::SUCCEED;
    }

    public function isExpired(): bool
    {
        return $this->status->value() === VerifyIdTokenStatus::EXPIRED;
    }

    /**
     * @return string|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }
}

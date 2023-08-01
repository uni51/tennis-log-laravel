<?php
declare(strict_types=1);

namespace App\Providers\Guards\Firebase;

final class VerifyTokenResponse
{
    private int $status;
    private ?int $userId;

    /**
     * VerifyTokenResponse constructor.
     * @param VerifyIdTokenStatus $status
     * @param int|null $userId
     */
    public function __construct(int $status, ?int $userId = null)
    {
        $this->status = $status;
        $this->userId = $userId;
    }

    public function isOK(): bool
    {
        return $this->status === VerifyIdTokenStatus::SUCCEED;
    }

    public function isExpired(): bool
    {
        return $this->status === VerifyIdTokenStatus::EXPIRED;
    }

    /**
     * @return string|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }
}

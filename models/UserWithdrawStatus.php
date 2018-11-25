<?php
declare(strict_types=1);

namespace app\models;

/**
 * @package app\models
 */
class UserWithdrawStatus
{
    const UNKNOWN   = 0;
    const WAITING   = 0x01;
    const CREATED   = 0x02;
    const CONFIRMED = 0x04;
    const REJECTED  = 0x10;
    const SUCCEEDED = 0x20;

    const COMPLETED = 0x30; // self::REJECTED | self::SUCCEEDED

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var UserWithdrawStatus[]
     */
    private static $statusList;

    /**
     * @param int $id
     * @param string $name
     */
    private function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isWaiting(): bool
    {
        return self::WAITING === $this->id;
    }

    /**
     * @return bool
     */
    public function isCreated(): bool
    {
        return self::CREATED === $this->id;
    }

    /**
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return self::CONFIRMED === $this->id;
    }

    /**
     * @return bool
     */
    public function isRejected(): bool
    {
        return self::REJECTED === $this->id;
    }

    /**
     * @return bool
     */
    public function isSucceeded(): bool
    {
        return self::SUCCEEDED === $this->id;
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return 0 !== (self::COMPLETED & $this->id);
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return self::UNKNOWN !== $this->id;
    }

    /**
     * @return UserWithdrawStatus[]
     */
    public static function getAll(): array
    {
        if (null === self::$statusList) {
            self::$statusList = [
                self::UNKNOWN   => new self(self::UNKNOWN, '<unknown>'),
                self::WAITING   => new self(self::WAITING, 'waiting'),
                self::CREATED   => new self(self::CREATED, 'created'),
                self::CONFIRMED => new self(self::CONFIRMED, 'confirmed'),
                self::REJECTED  => new self(self::REJECTED, 'rejected'),
                self::SUCCEEDED => new self(self::SUCCEEDED, 'succeeded'),
            ];
        }
        return self::$statusList;
    }

    /**
     * @param int $id
     * @return UserWithdrawStatus
     */
    public static function forId(int $id): UserWithdrawStatus
    {
        $list = self::getAll();
        return $list[$id] ?? $list[self::UNKNOWN];
    }
}
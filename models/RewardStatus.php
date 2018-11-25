<?php
declare(strict_types=1);


namespace app\models;

/**
 * @package app\models
 */
class RewardStatus
{
    const UNKNOWN = 0;
    const WAITING = 1;
    const CLAIMED = 2;
    const REJECTED = 3;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var RewardStatus[]
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
    public function isClaimed(): bool
    {
        return self::CLAIMED === $this->id;
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
    public function isValid(): bool
    {
        return self::UNKNOWN !== $this->id;
    }

    /**
     * @return RewardStatus[]
     */
    public static function getAll(): array
    {
        if (null === self::$statusList) {
            self::$statusList = [
                self::UNKNOWN => new self(self::UNKNOWN, '<unknown>'),
                self::WAITING => new self(self::WAITING, 'waiting'),
                self::CLAIMED => new self(self::CLAIMED, 'claimed'),
                self::REJECTED => new self(self::REJECTED, 'rejected'),
            ];
        }
        return self::$statusList;
    }

    /**
     * @param int $id
     * @return RewardStatus
     */
    public static function forId(int $id): RewardStatus
    {
        $list = self::getAll();
        return $list[$id] ?? $list[self::UNKNOWN];
    }
}
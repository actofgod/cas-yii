<?php
declare(strict_types=1);

namespace app\models;


class PostPackageStatus
{
    const UNKNOWN  = 0;
    const WAITING  = 1;
    const SHIPPING = 2;
    const RETURNED = 3;
    const COMPLETE = 4;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var PostPackageStatus[]
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
    public function isShipping(): bool
    {
        return self::SHIPPING === $this->id;
    }

    /**
     * @return bool
     */
    public function isReturned(): bool
    {
        return self::RETURNED === $this->id;
    }

    /**
     * @return bool
     */
    public function isComplete(): bool
    {
        return self::COMPLETE === $this->id;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return self::UNKNOWN !== $this->id;
    }

    /**
     * @return PostPackageStatus[]
     */
    public static function getAll(): array
    {
        if (null === self::$statusList) {
            self::$statusList = [
                self::UNKNOWN  => new self(self::UNKNOWN, '<unknown>'),
                self::WAITING  => new self(self::WAITING, 'waiting'),
                self::SHIPPING => new self(self::SHIPPING, 'shipping'),
                self::RETURNED => new self(self::RETURNED, 'returned'),
                self::COMPLETE => new self(self::COMPLETE, 'complete'),
            ];
        }
        return self::$statusList;
    }

    /**
     * @param int $id
     * @return PostPackageStatus
     */
    public static function forId(int $id): PostPackageStatus
    {
        $list = self::getAll();
        return $list[$id] ?? $list[self::UNKNOWN];
    }
}
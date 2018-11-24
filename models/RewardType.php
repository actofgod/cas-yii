<?php
declare(strict_types=1);

namespace app\models;

/**
 * @package App\Entity
 */
class RewardType
{
    const UNKNOWN = 0;
    const ITEM = 1;
    const MONEY = 2;
    const POINTS = 3;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var RewardType[]
     */
    private static $typeList;

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
    public function isItem(): bool
    {
        return self::ITEM === $this->id;
    }

    /**
     * @return bool
     */
    public function isMoney(): bool
    {
        return self::MONEY === $this->id;
    }

    /**
     * @return bool
     */
    public function isPoints(): bool
    {
        return self::POINTS === $this->id;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return self::UNKNOWN !== $this->id;
    }

    /**
     * @return RewardType[]
     */
    public static function getAll(): array
    {
        if (null === self::$typeList) {
            self::$typeList = [
                self::UNKNOWN => new self(self::UNKNOWN, '<unknown>'),
                self::ITEM => new self(self::ITEM, 'item'),
                self::MONEY => new self(self::MONEY, 'money'),
                self::POINTS => new self(self::POINTS, 'points'),
            ];
        }
        return self::$typeList;
    }

    /**
     * @param int $id
     * @return RewardType
     */
    public static function forId(int $id): RewardType
    {
        $list = self::getAll();
        return $list[$id] ?? $list[self::UNKNOWN];
    }

    /**
     * @param string $name
     * @return RewardType
     */
    public static function forType(string $name): RewardType
    {
        foreach (self::getAll() as $rewardType) {
            if ($rewardType->name === $name) {
                return $rewardType;
            }
        }
        return self::forId(self::UNKNOWN);
    }
}
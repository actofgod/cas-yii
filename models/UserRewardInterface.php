<?php
declare(strict_types=1);

namespace app\models;

/**
 * @property int $id
 * @property UserReward $userReward
 */
interface UserRewardInterface extends \JsonSerializable
{
    /**
     *
     */
    public function claim(): void;

    /**
     *
     */
    public function reject(): void;

    /**
     * @return bool
     */
    public function canConvert(): bool;

    /**
     *
     */
    public function convert(): void;
}
<?php
declare(strict_types=1);

namespace app\models;

/**
 * @package app\models
 */
interface RewardInterface
{
    public function getReward();
    public function isAvailable(): bool;
    //public function apply(User $user): UserReward;
}
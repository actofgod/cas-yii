<?php
declare(strict_types=1);

namespace app\models;

use yii\db\ActiveQuery;

/**
 * @property int $id
 * @property Reward $reward
 */
interface RewardInterface
{
    /**
     * @return ActiveQuery
     */
    public function getReward();

    /**
     * @return bool
     */
    public function isAvailable(): bool;

    /**
     * @param UserRewardInterface $userReward
     * @return bool
     */
    public function process(UserRewardInterface $userReward): bool;
}

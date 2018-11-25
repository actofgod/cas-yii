<?php
declare(strict_types=1);

namespace app\models\UserReward;

use app\models\UserReward;
use app\models\UserRewardInterface;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $amount
 * @property UserReward $userReward
 */
class PointsUserReward extends ActiveRecord implements UserRewardInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_reward_points';
    }

    /**
     * @inheritdoc
     */
    public function getUserReward()
    {
        return $this->hasOne(UserReward::class, ['id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function claim(): void
    {
        $user = $this->userReward->user;
        $user->updateCounters(['points_amount' => $this->amount]);
    }

    /**
     * @inheritdoc
     */
    public function reject(): void
    {
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): array
    {
        return [
            'amount' => $this->amount,
        ];
    }
}

<?php
declare(strict_types=1);

namespace app\models\Reward;

use app\models\Reward;
use app\models\RewardInterface;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $min_amount
 * @property int $max_amount
 * @property Reward $reward
 */
class PointsReward extends ActiveRecord implements RewardInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reward_points';
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getReward()
    {
        return $this->hasOne(Reward::class, ['id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function isAvailable(): bool
    {
        return true;
    }
}

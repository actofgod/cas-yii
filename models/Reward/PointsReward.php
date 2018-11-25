<?php
declare(strict_types=1);

namespace app\models\Reward;

use app\models\Reward;
use app\models\RewardInterface;
use app\models\UserReward\PointsUserReward;
use app\models\UserRewardInterface;
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'min_amount', 'max_amount'], 'required'],
            [['id', 'min_amount', 'max_amount'], 'integer'],
            [['id'], 'unique'],
            [
                ['id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Reward::class,
                'targetAttribute' => ['id' => 'id']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'min_amount' => 'Min Amount',
            'max_amount' => 'Max Amount',
        ];
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

    /**
     * @inheritdoc
     */
    public function process(UserRewardInterface $userReward): bool
    {
        if ($userReward instanceof PointsUserReward) {
            $userReward->amount = random_int($this->min_amount, $this->max_amount);
            return true;
        }
        return false;
    }
}

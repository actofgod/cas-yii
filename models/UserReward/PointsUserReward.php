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
    public function rules()
    {
        return [
            [['id', 'amount'], 'required'],
            [['id', 'amount'], 'integer'],
            [['id'], 'unique'],
            [
                ['id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => UserReward::class,
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
            'amount' => 'Amount',
        ];
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
    public function canConvert(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function convert(): void
    {
        throw new \BadMethodCallException();
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

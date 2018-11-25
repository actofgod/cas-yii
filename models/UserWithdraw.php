<?php
declare(strict_types=1);

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $user_id
 * @property int $reward_id
 * @property int $amount
 * @property int $status_id
 * @property string|null $transaction_id
 * @property string $created_at
 * @property-read UserReward\MoneyUserReward $reward
 * @property-read UserWithdrawStatus $status
 * @property-read User $user
 * @property-read UserWithdrawHistoryEntry[] $history
 */
class UserWithdraw extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_withdraws';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_id', 'amount', 'user_id', 'reward_id', 'created_at'], 'required'],
            [['status_id', 'amount', 'user_id', 'reward_id'], 'integer'],
            [['created_at'], 'safe'],
            [['transaction_id'], 'string', 'max' => 127],
            [
                ['reward_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => UserReward\MoneyUserReward::class,
                'targetAttribute' => ['reward_id' => 'id']
            ],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']
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
            'status_id' => 'Status',
            'amount' => 'Amount',
            'user_id' => 'User',
            'reward_id' => 'Reward',
            'created_at' => 'Created At',
            'transaction_id' => 'Transaction ID',
        ];
    }

    /**
     * @return UserWithdrawStatus
     */
    public function getStatus(): UserWithdrawStatus
    {
        return UserWithdrawStatus::forId($this->status_id);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUserReward(): ActiveQuery
    {
        return $this->hasOne(UserReward\MoneyUserReward::class, ['id' => 'reward_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getHistory(): ActiveQuery
    {
        return $this->hasMany(UserWithdrawHistoryEntry::class, ['withdraw_id' => 'id']);
    }
}

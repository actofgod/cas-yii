<?php
declare(strict_types=1);

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $item_id
 * @property int $user_id
 * @property int $reward_id
 * @property int $status_id
 * @property string $created_at
 *
 * @property-read Item $item
 * @property-read User $user
 * @property-read UserReward\ItemUserReward
 * @property-read PostPackageHistoryEntry[] $history
 */
class PostPackage extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_post_packages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_id', 'item_id', 'user_id', 'reward_id', 'created_at'], 'required'],
            [['status_id', 'item_id', 'user_id', 'reward_id'], 'integer'],
            [['created_at'], 'safe'],
            [
                ['item_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Item::class,
                'targetAttribute' => ['item_id' => 'id']
            ],
            [
                ['reward_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => UserReward\ItemUserReward::class,
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
            'item_id' => 'Item',
            'user_id' => 'User',
            'reward_id' => 'Reward',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return PostPackageStatus
     */
    public function getStatus(): PostPackageStatus
    {
        return PostPackageStatus::forId($this->status_id);
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
    public function getItem(): ActiveQuery
    {
        return $this->hasOne(Item::class, ['id' => 'item_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUserReward(): ActiveQuery
    {
        return $this->hasOne(UserReward\ItemUserReward::class, ['id' => 'reward_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getHistory(): ActiveQuery
    {
        return $this->hasMany(PostPackageHistoryEntry::class, ['package_id' => 'id']);
    }
}
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
 * @property-read Item $item
 * @property-read User $user
 * @property-read UserReward\ItemUserReward
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
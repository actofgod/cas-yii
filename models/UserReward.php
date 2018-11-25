<?php
declare(strict_types=1);

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $user_id
 * @property int $reward_id
 * @property int $status_id
 * @property string $created_at
 * @property string $expire_in
 * @property UserRewardInterface $actualReward
 * @property \DateTimeInterface $createdAt
 * @property \DateTimeInterface $expireIn
 * @property RewardStatus $status
 * @property User $user
 * @property Reward $reward
 */
class UserReward extends ActiveRecord implements \JsonSerializable
{
    private static $actualRewardClassMap = [
        RewardType::ITEM => UserReward\ItemUserReward::class,
        RewardType::MONEY => UserReward\MoneyUserReward::class,
        RewardType::POINTS => UserReward\PointsUserReward::class,
    ];

    /**
     * @var RewardStatus
     */
    private $status;

    /**
     * @var \DateTimeInterface
     */
    private $createdAt;

    /**
     * @var \DateTimeInterface
     */
    private $expireIn;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_rewards';
    }

    /**
     * @return RewardStatus
     */
    public function getStatus(): RewardStatus
    {
        if (null === $this->status) {
            $this->status = RewardType::forId($this->status_id);
        }
        return $this->status;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        if (null === $this->createdAt) {
            $this->createdAt = new \DateTime($this->created_at);
        }
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        $this->created_at = $createdAt->format('Y-m-d H:i:s');
    }

    /**
     * @return \DateTimeInterface
     */
    public function getExpireIn(): \DateTimeInterface
    {
        if (null === $this->expireIn) {
            $this->expireIn = new \DateTime($this->expire_in);
        }
        return $this->expireIn;
    }

    /**
     * @param \DateTimeInterface $expireIn
     */
    public function setExpireIn($expireIn)
    {
        $this->expireIn = $expireIn;
        $this->expire_in = $expireIn->format('Y-m-d H:i:s');
    }

    /**
     * @return UserRewardInterface
     */
    public function factoryActualReward(): UserRewardInterface
    {
        $className = self::$actualRewardClassMap[$this->reward->type_id];

        $instance = new $className();
        $instance->id = $this->id;

        return $instance;
    }

    /**
     * @return ActiveQuery
     */
    public function getReward(): ActiveQuery
    {
        return $this->hasOne(Reward::class, ['id' => 'reward_id']);
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
    public function getActualReward(): ActiveQuery
    {
        return $this->hasOne(self::$actualRewardClassMap[$this->reward->type_id], ['id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): array
    {
        $result = $this->actualReward->jsonSerialize();
        $result['type'] = $this->reward->getType()->getName();
        return $result;
    }
}

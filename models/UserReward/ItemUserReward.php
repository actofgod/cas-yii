<?php
declare(strict_types=1);

namespace app\models\UserReward;


use app\models\Item;
use app\models\Reward\ItemReward;
use app\models\UserReward;
use app\models\UserRewardInterface;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property UserReward $userReward
 */
class ItemUserReward extends ActiveRecord implements UserRewardInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_reward_items';
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
    }

    /**
     * @inheritdoc
     */
    public function reject(): void
    {
        $reward = $this->userReward->reward->actualReward;
        if ($reward instanceof ItemReward) {
            $reward->item->updateCounters(['quantity' => 1]);
        }
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): array
    {
        /** @var Item $item */
        $item =  $this->userReward->reward->item;
        return [
            'item' => [
                'id'   => $item->id,
                'name' => $item->name,
            ],
        ];
    }
}

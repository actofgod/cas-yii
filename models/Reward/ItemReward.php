<?php
declare(strict_types=1);

namespace app\models\Reward;

use app\models\Item;
use app\models\Reward;
use app\models\RewardInterface;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $itemId
 * @property Item $item
 * @property Reward $reward
 */
class ItemReward extends ActiveRecord implements RewardInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reward_items';
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::class, ['id' => 'item_id']);
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
        return $this->item->quantity > 0;
    }
}
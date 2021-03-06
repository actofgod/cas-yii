<?php
declare(strict_types=1);

namespace app\models\Reward;

use app\models\Item;
use app\models\Reward;
use app\models\RewardInterface;
use app\models\UserReward\ItemUserReward;
use app\models\UserRewardInterface;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $item_id
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'item_id'], 'required'],
            [['id', 'item_id'], 'integer'],
            [['id'], 'unique'],
            [
                ['item_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Item::class,
                'targetAttribute' => ['item_id' => 'id']
            ],
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
            'item_id' => 'Item',
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

    /**
     * @inheritdoc
     */
    public function process(UserRewardInterface $userReward): bool
    {
        if ($userReward instanceof ItemUserReward) {
            $this->item->updateCounters(['quantity' => -1]);
            return true;
        }
        return false;
    }
}
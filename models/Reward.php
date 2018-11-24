<?php
declare(strict_types=1);

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $type_id
 * @property float $weight
 * @property RewardInterface $actualReward
 */
class Reward extends ActiveRecord
{
    /**
     * @var RewardType
     */
    private $type;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rewards';
    }

    /**
     * @return RewardType
     */
    public function getType(): RewardType
    {
        if (null === $this->type) {
            $this->type = RewardType::forId($this->type_id);
        }
        return $this->type;
    }

    /**
     * @return RewardInterface|null
     */
    public function getActualReward()
    {
        if ($this->getType()->isItem()) {
            return $this->hasOne(Reward\ItemReward::class, ['id' => 'id']);
        } elseif ($this->getType()->isMoney()) {
            return $this->hasOne(Reward\MoneyReward::class, ['id' => 'id']);
        } else {
            return $this->hasOne(Reward\PointsReward::class, ['id' => 'id']);
        }
        return null;
    }
}
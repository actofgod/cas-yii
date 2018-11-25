<?php
declare(strict_types=1);

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $roulette_id
 * @property int $type_id
 * @property float $weight
 * @property RewardInterface $actualReward
 * @property Roulette $roulette
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
     * @return ActiveQuery
     */
    public function getRoulette(): ActiveQuery
    {
        return $this->hasOne(Roulette::class, ['id' => 'roulette_id']);
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
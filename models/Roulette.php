<?php
declare(strict_types=1);

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @param int $id
 * @param int $max_money_amount
 * @param int $current_money_amount
 * @param Reward[] $rewardList
 */
class Roulette extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'roulette';
    }

    /**
     * @return int
     */
    public function getAvailableMoneyAmount(): int
    {
        return $this->max_money_amount - $this->current_money_amount;
    }

    /**
     * @return ActiveQuery
     */
    public function getRewardList(): ActiveQuery
    {
        return $this->hasMany(Reward::class, ['roulette_id' => 'id']);
    }
}

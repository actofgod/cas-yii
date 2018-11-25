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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['max_money_amount', 'current_money_amount'], 'required'],
            [['max_money_amount', 'current_money_amount'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'max_money_amount' => 'Max Money Amount',
            'current_money_amount' => 'Current Money Amount',
        ];
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

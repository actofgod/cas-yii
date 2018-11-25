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
 * @property-read RewardInterface $actualReward
 * @property-read Roulette $roulette
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['roulette_id', 'type_id', 'weight'], 'required'],
            [['roulette_id', 'type_id'], 'integer'],
            [['weight'], 'number'],
            [
                ['roulette_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Roulette::class,
                'targetAttribute' => ['roulette_id' => 'id']
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
            'roulette_id' => 'Roulette',
            'type_id' => 'Type',
            'weight' => 'Weight',
        ];
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
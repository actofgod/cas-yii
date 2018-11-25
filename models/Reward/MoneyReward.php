<?php
declare(strict_types=1);

namespace app\models\Reward;

use app\models\Reward;
use app\models\RewardInterface;
use app\models\UserReward\MoneyUserReward;
use app\models\UserRewardInterface;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $min_amount
 * @property int $max_amount
 * @property Reward $reward
 */
class MoneyReward extends ActiveRecord implements RewardInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reward_money';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'min_amount', 'max_amount'], 'required'],
            [['id', 'min_amount', 'max_amount'], 'integer'],
            [['id'], 'unique'],
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
            'min_amount' => 'Min Amount',
            'max_amount' => 'Max Amount',
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
        $roulette = $this->reward->roulette;
        return $this->min_amount < $roulette->getAvailableMoneyAmount();
    }

    /**
     * @inheritdoc
     */
    public function process(UserRewardInterface $userReward): bool
    {
        if ($userReward instanceof MoneyUserReward) {
            $roulette = $this->reward->roulette;
            $available = $roulette->getAvailableMoneyAmount();
            if ($this->min_amount > $available) {
                return false;
            }
            $max = $available > $this->max_amount ? $this->max_amount : $available;
            $userReward->amount = random_int($this->min_amount, $max);
            $roulette->updateCounters(['current_money_amount' => $userReward->amount]);
            return true;
        }
        return false;
    }
}

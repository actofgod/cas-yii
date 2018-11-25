<?php
declare(strict_types=1);

namespace app\models\UserReward;

use app\models\UserReward;
use app\models\UserRewardInterface;
use app\models\UserWithdraw;
use app\models\UserWithdrawStatus;
use app\services\MoneyToPointsConverter;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $amount
 * @property UserReward $userReward
 */
class MoneyUserReward extends ActiveRecord implements UserRewardInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_reward_money';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'amount'], 'required'],
            [['id', 'amount'], 'integer'],
            [['id'], 'unique'],
            [
                ['id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => UserReward::class,
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
            'amount' => 'Amount',
        ];
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
        $withdraw = new UserWithdraw();
        $withdraw->user_id = $this->userReward->user_id;
        $withdraw->reward_id = $this->id;
        $withdraw->amount = $this->amount;
        $withdraw->status_id = UserWithdrawStatus::WAITING;
        $withdraw->created_at = date('Y-m-d H:i:s');
        $withdraw->save();
    }

    /**
     * @inheritdoc
     */
    public function reject(): void
    {
        $roulette = $this->userReward->reward->roulette;
        $roulette->updateCounters(['current_money_amount' => -$this->amount]);
    }

    /**
     * @inheritdoc
     */
    public function canConvert(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function convert(): void
    {
        $roulette = $this->userReward->reward->roulette;
        $roulette->updateCounters(['current_money_amount' => -$this->amount]);

        $user = $this->userReward->user;
        $converter = new MoneyToPointsConverter();
        $amount = $converter->convert($this->amount);
        $user->updateCounters(['points_amount' => $amount]);
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): array
    {
        return [
            'amount' => $this->amount,
        ];
    }
}

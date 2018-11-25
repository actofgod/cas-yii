<?php
declare(strict_types=1);

namespace app\models\UserReward;

use app\models\UserReward;
use app\models\UserRewardInterface;
use app\models\UserWithdraw;
use app\models\UserWithdrawStatus;
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
    public function jsonSerialize(): array
    {
        return [
            'amount' => $this->amount,
        ];
    }
}

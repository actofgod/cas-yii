<?php
declare(strict_types=1);

namespace app\services;

use app\models\Reward;
use app\models\RewardStatus;
use app\models\Roulette;
use app\models\UserReward;
use Yii;
use yii\web\User;

/**
 * @package app\services
 */
class RouletteService
{
    private $availableRewardList;

    /**
     * @var Roulette
     */
    private $roulette;

    public function __construct()
    {
        $this->roulette = Roulette::find(1)->one();
    }

    /**
     * @return Reward[]
     */
    public function getAvailableRewards(): array
    {
        if (null === $this->availableRewardList) {
            $this->availableRewardList = [];
            /** @var Reward $reward */
            foreach ($this->roulette->rewardList as $reward) {
                if ($reward->actualReward->isAvailable()) {
                    $this->availableRewardList[] = $reward;
                }
            }
        }
        return $this->availableRewardList;
    }

    /**
     * @return UserReward|null
     */
    public function rotate(): ?UserReward
    {
        $userReward = null;
        $retries = 0;
        do {
            $reward = $this->findReward();

            $transaction = Yii::$app->db->beginTransaction();
            try {
                $userReward = new UserReward();
                $userReward->user_id = Yii::$app->user->id;
                $userReward->reward_id = $reward->id;
                $userReward->setCreatedAt(new \DateTime());
                $userReward->setExpireIn(new \DateTime('+10 min'));
                $userReward->status_id = RewardStatus::WAITING;
                $userReward->save();

                $actualReward = $userReward->factoryActualReward();
                if (!$reward->actualReward->process($actualReward)) {
                    throw new \RuntimeException('Failed to process user reward');
                }
                $actualReward->save();

                $transaction->commit();
            } catch (\Throwable $exception) {
                echo $exception->getMessage();
                $transaction->rollBack();
                $userReward = null;
                $retries++;
            }
        } while ($userReward === null && $retries < 3);

        if (null === $userReward) {
            var_dump($this->getAvailableRewards());
            exit();
        }

        return $userReward;
    }

    /**
     * @param UserReward $userReward
     */
    public function claim(UserReward $userReward): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $userReward->actualReward->claim();
            $userReward->status_id = RewardStatus::CLAIMED;
            $userReward->save();
            $transaction->commit();
        } catch (\Throwable $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    /**
     * @param UserReward $userReward
     */
    public function reject(UserReward $userReward): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $userReward->actualReward->reject();
            $userReward->status_id = RewardStatus::REJECTED;
            $userReward->save();
            $transaction->commit();
        } catch (\Throwable $exception) {
            $transaction->rollBack();
        }
    }

    /**
     *
     */
    public function rejectExpiredRewards(): void
    {
        $expiredDataSet = UserReward::find()->where(
            'status_id = :status AND expire_in <= :now',
            [
                ':status' => RewardStatus::WAITING,
                ':now'    => date('Y-m-d H:i:s'),
            ]
        )->all();
        foreach ($expiredDataSet as $record) {
            $this->reject($record);
        }
    }

    /**
     * @param User $user
     * @return UserReward|null
     */
    public function findCurrentReward(User $user): ?UserReward
    {
        $this->rejectExpiredRewards();
        return UserReward::find()->where(
            'user_id = :userId AND status_id = :status AND expire_in > :now',
            [
                ':userId' => $user->id,
                ':status' => RewardStatus::WAITING,
                ':now'    => date('Y-m-d H:i:s'),
            ]
        )->one();
    }

    /**
     * @return Reward
     */
    protected function findReward(): Reward
    {
        $intervals = [];
        $max = 0;
        foreach ($this->getAvailableRewards() as $reward) {
            $max += $reward->weight;
            $intervals[(string) $max] = $reward;
        }
        $value = random_int(0, PHP_INT_MAX) / PHP_INT_MAX * $max;
        foreach ($intervals as $val => $reward) {
            if ($value < (float) $val) {
                return $reward;
            }
        }
        return end($intervals);
    }
}

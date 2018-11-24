<?php
declare(strict_types=1);

namespace app\services;

use app\models\Reward;

/**
 * @package app\services
 */
class RouletteService
{
    private $availableRewardList;

    /**
     * @return Reward[]
     */
    public function getAvailableRewards(): array
    {
        if (null === $this->availableRewardList) {
            $this->availableRewardList = [];
            /** @var Reward $reward */
            foreach (Reward::find()->all() as $reward) {
                if ($reward->actualReward->isAvailable()) {
                    $this->availableRewardList[] = $reward;
                }
            }
        }
        return $this->availableRewardList;
    }

    /**
     * @return Reward
     */
    public function rotate(): Reward
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
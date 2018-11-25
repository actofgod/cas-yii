<?php
declare(strict_types=1);

namespace app\services;

/**
 * @package app\services
 */
class MoneyToPointsConverter
{
    /**
     * @var int
     */
    private $conversionRate;

    /**
     */
    public function __construct()
    {
        $this->conversionRate = 10;
    }

    /**
     * @param int $amount
     * @return int
     */
    public function convert(int $amount): int
    {
        return $this->conversionRate * $amount;
    }
}
<?php
declare(strict_types=1);

namespace app\services;

/**
 * @package app\services
 */
class WithdrawDummyGateService
{
    /**
     * @param array $withdrawList
     * @return array
     */
    public function sendBatch(array $withdrawList): array
    {
        $result = [
            'result' => []
        ];
        foreach ($withdrawList as $index => $item) {
            if (0 === $index % 2) {
                $result['result'][] = null;
            } else {
                $result['result'][] = uniqid('');
            }
        }
        return $result;
    }
}
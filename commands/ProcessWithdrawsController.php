<?php
declare(strict_types=1);

namespace app\commands;

use app\models\UserWithdraw;
use app\models\UserWithdrawStatus;
use app\services\WithdrawDummyGateService;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * @package app\commands
 */
class ProcessWithdrawsController extends Controller
{
    /**
     * @var WithdrawDummyGateService
     */
    private $withdrawGate;

    /**
     * @return int
     */
    public function actionIndex(): int
    {
        $blockSize = 10;
        $max = 10000;
        $count = 0;
        $dataSet = $this->fetchBlock($blockSize);
        while (count($dataSet) > 0) {
            $request = [];
            /** @var UserWithdraw[] $withdrawList */
            $withdrawList = [];
            /** @var UserWithdraw $withdraw */
            foreach ($dataSet as $withdraw) {
                $request[] = [
                    'email' => $withdraw->user,
                    'user_id' => $withdraw->user_id,
                    'amount' => $withdraw->amount,
                ];
                $withdrawList[] = $withdraw;
            }
            $response = $this->getWithdrawGate()->sendBatch(array_values($request));
            foreach ($response['result'] as $index => $res) {
                $withdraw = $withdrawList[$index];
                if (empty($res)) {
                    $withdraw->status_id = UserWithdrawStatus::REJECTED;
                } else {
                    $withdraw->status_id = UserWithdrawStatus::CREATED;
                    $withdraw->transaction_id = $res;
                }
                $withdraw->save();
                $count++;
            }
            if ($count >= $max) {
                break;
            }
            $dataSet = $this->fetchBlock($blockSize);
        }
        return ExitCode::OK;
    }

    /**
     * @param int $blockSize
     * @return UserWithdraw[]
     */
    private function fetchBlock(int $blockSize)
    {
        return UserWithdraw::find()->where(
            'status_id = :statusId',
            [':statusId' => UserWithdrawStatus::WAITING]
        )->orderBy('id')->limit($blockSize)->all();
    }

    /**
     * @return WithdrawDummyGateService
     */
    private function getWithdrawGate()
    {
        if (null === $this->withdrawGate) {
            $this->withdrawGate = new WithdrawDummyGateService();
        }
        return $this->withdrawGate;
    }
}

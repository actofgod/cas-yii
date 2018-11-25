<?php
declare(strict_types=1);

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @param int $id
 * @param int $withdraw_id
 * @param int $status_id
 * @param string $date
 * @param \DateTimeInterface $dateInstance
 * @param UserWithdraw $withdraw
 */
class UserWithdrawHistoryEntry extends ActiveRecord
{
    /**
     * @var \DateTimeInterface
     */
    private $dateInstance;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_withdraw_history';
    }

    /**
     * @return ActiveQuery
     */
    public function getWithdraw(): ActiveQuery
    {
        return $this->hasOne(UserWithdraw::class, ['id' => 'withdraw_id']);
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDateInstance(): \DateTimeInterface
    {
        if (null === $this->dateInstance) {
            $this->dateInstance = new \DateTime($this->date);
        }
        return $this->dateInstance;
    }
}
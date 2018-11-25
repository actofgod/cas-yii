<?php
declare(strict_types=1);

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $withdraw_id
 * @property int $status_id
 * @property string $date
 * @property \DateTimeInterface $dateInstance
 * @property UserWithdraw $withdraw
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_id', 'withdraw_id', 'date'], 'required'],
            [['status_id', 'withdraw_id'], 'integer'],
            [['date'], 'safe'],
            [
                ['withdraw_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => UserWithdraw::class,
                'targetAttribute' => ['withdraw_id' => 'id'],
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
            'status_id' => 'Status',
            'withdraw_id' => 'Withdraw',
            'date' => 'Date',
        ];
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
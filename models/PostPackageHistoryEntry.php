<?php
declare(strict_types=1);

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $status_id
 * @property int $package_id
 * @property string $date
 * @property-read \DateTimeInterface $dateInstance
 * @property-read PostPackage $package
 */
class PostPackageHistoryEntry extends ActiveRecord
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
        return 'user_post_package_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_id', 'package_id', 'date'], 'required'],
            [['status_id', 'package_id'], 'integer'],
            [['date'], 'safe'],
            [
                ['package_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => PostPackage::class,
                'targetAttribute' => ['package_id' => 'id']
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
            'package_id' => 'Package',
            'date' => 'Date',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getPackage(): ActiveQuery
    {
        return $this->hasOne(PostPackage::class, ['id' => 'package_id']);
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
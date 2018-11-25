<?php
declare(strict_types=1);

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @param int $id
 * @param int $package_id
 * @param int $status_id
 * @param string $date
 * @param \DateTimeInterface $dateInstance
 * @param PostPackage $package
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
     * @return ActiveQuery
     */
    public function getWithdraw(): ActiveQuery
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
<?php
declare(strict_types=1);

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $name
 * @property int $quantity
 * @property string $created_at
 */
class Item extends ActiveRecord
{
    /**
     * @var \DateTimeInterface
     */
    private $createdAt;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'quantity', 'created_at'], 'required'],
            [['quantity'], 'integer'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'quantity' => 'Quantity',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        if (null === $this->createdAt) {
            $this->createdAt = new \DateTime($this->created_at);
        }
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $value
     * @return Item
     */
    public function setCreatedAt(\DateTimeInterface $value): Item
    {
        $this->createdAt = $value;
        $this->created_at = $value->format('Y-m-d H:i:s');
        return $this;
    }
}

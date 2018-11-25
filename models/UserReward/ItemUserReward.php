<?php
declare(strict_types=1);

namespace app\models\UserReward;

use app\models\Item;
use app\models\PostPackage;
use app\models\PostPackageStatus;
use app\models\Reward\ItemReward;
use app\models\UserReward;
use app\models\UserRewardInterface;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property UserReward $userReward
 */
class ItemUserReward extends ActiveRecord implements UserRewardInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_reward_items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['id'], 'unique'],
            [
                ['id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => UserReward::class,
                'targetAttribute' => ['id' => 'id']
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
        ];
    }

    /**
     * @inheritdoc
     */
    public function getUserReward()
    {
        return $this->hasOne(UserReward::class, ['id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function claim(): void
    {
        /** @var Item $item */
        $item =  $this->userReward->reward->actualReward->item;

        $package = new PostPackage();
        $package->item_id = $item->id;
        $package->user_id = $this->userReward->user_id;
        $package->reward_id = $this->id;
        $package->status_id = PostPackageStatus::WAITING;
        $package->created_at = date('Y-m-d H:i:s');
        $package->save();
    }

    /**
     * @inheritdoc
     */
    public function reject(): void
    {
        $reward = $this->userReward->reward->actualReward;
        if ($reward instanceof ItemReward) {
            $reward->item->updateCounters(['quantity' => 1]);
        }
    }

    /**
     * @inheritdoc
     */
    public function canConvert(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function convert(): void
    {
        throw new \BadMethodCallException();
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): array
    {
        /** @var Item $item */
        $item =  $this->userReward->reward->actualReward->item;
        return [
            'item' => [
                'id'   => $item->id,
                'name' => $item->name,
            ],
        ];
    }
}

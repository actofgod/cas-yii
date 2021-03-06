<?php
declare(strict_types=1);

namespace app\controllers;

use app\models\Reward;
use app\models\RewardStatus;
use app\models\UserReward;
use app\services\RouletteService;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

/**
 * @package app\controllers
 */
class RouletteController extends Controller
{
    /**
     * @var RouletteService
     */
    private $service;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $reward = $this->getService()->findCurrentReward(Yii::$app->user);
        return $this->render('index', [
            'reward' => $reward,
        ]);
    }

    /**
     * @return array
     */
    public function actionRotate(): array
    {
        $reward = $this->getService()->findCurrentReward(Yii::$app->user);
        if (null === $reward) {
            $reward = $this->getService()->rotate();
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'success' => true,
            'reward'  => $reward,
        ];
    }

    /**
     * @return array
     */
    public function actionClaim(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $reward = $this->getService()->findCurrentReward(Yii::$app->user);
        if (null === $reward) {
            return [
                'success' => false,
                'error' => 'Actual reward does not exists',
            ];
        }
        $this->getService()->claim($reward);
        return [
            'success' => true,
            'reward'  => $reward,
        ];
    }

    /**
     * @return array
     */
    public function actionReject(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $reward = $this->getService()->findCurrentReward(Yii::$app->user);
        if (null === $reward) {
            return [
                'success' => false,
                'error' => 'Actual reward does not exists',
            ];
        }
        $this->getService()->reject($reward);
        return [
            'success' => true,
            'reward'  => $reward,
        ];
    }

    /**
     * @return array
     */
    public function actionConvert(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $reward = $this->getService()->findCurrentReward(Yii::$app->user);
        if (null === $reward) {
            return [
                'success' => false,
                'error' => 'Actual reward does not exists',
            ];
        }
        $this->getService()->convert($reward);
        return [
            'success' => true,
            'reward'  => $reward,
        ];
    }

    /**
     * @return RouletteService
     */
    private function getService(): RouletteService
    {
        if (null === $this->service) {
            $this->service = new RouletteService();
        }
        return $this->service;
    }
}
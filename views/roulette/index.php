<?php

/**
 * @var $this yii\web\View
 * @var \app\models\UserReward $reward
 */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <?php if (null === $reward): ?>
        <div class="jumbotron">
            <h1>Press magic button!</h1>
            <p>You can win</p>
            <p><button class="btn btn-lg btn-success">Magic button</button></p>
        </div>
    <?php else: ?>
        <div class="jumbotron">
            <?= $reward->reward->getType() ?>
            <p><button class="btn btn-lg btn-success">Get started with Yii</button></p>
        </div>
    <?php endif; ?>
</div>

<?php

/**
 * @var $this yii\web\View
 * @var \app\models\UserReward $reward
 */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="jumbotron" id="roulette"<?php if (null !== $reward): ?> style="display:none;"<?php endif; ?>>
        <h1>Press magic button!</h1>
        <p>You can win</p>
        <p><button class="btn btn-lg btn-success" id="magic-button">Get my award</button></p>
    </div>
    <div class="jumbotron" id="result"<?php if (null === $reward): ?> style="display:none;"<?php endif; ?>>
        <p>
            Your reward is: <span id="reward-block"></span>
        </p>
        <p>
            <button class="btn btn-lg btn-success" data-action="claim">Claim reward</button>
            <button id="convert-button" class="btn btn-lg btn-warning" data-action="convert">Convert to points</button>
            <button class="btn btn-lg btn-danger" data-action="reject">Reject reward</button>
        </p>
    </div>
</div>

<script type="text/javascript">
    (function () {
        var sendRequest = function (action, successCallback) {
            var self = this;
            jQuery.ajax({
                url: '?r=roulette/' + action,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        successCallback.call(self, response.reward);
                    } else {
                        alert(response.error);
                    }
                },
                error: function () {
                    alert('API call error');
                }
            })
        };
        var roulette = jQuery('#roulette');
        var result = jQuery('#result');
        var showResult = function (res) {
            var resultText = '';
            if (res.type === 'item') {
                resultText = res.item.name;
                jQuery('#convert-button').css({display:'none'});
            } else if (res.type === 'money') {
                resultText = '$' + res.amount;
                jQuery('#convert-button').css({display:'inline-block'});
            } else if (res.type === 'points') {
                resultText = res.amount + ' bonus points';
                jQuery('#convert-button').css({display:'none'});
            }
            jQuery('#reward-block').text(resultText);
            roulette.css({display:'none'});
            result.css({display:'block'});
        };
        var showRoulette = function () {
            roulette.css({display:'block'});
            result.css({display:'none'});
            jQuery('#reward-block').text('');
        };
        jQuery(document).ready(function () {
            <?php if (null !== $reward): ?>
            showResult(<?= json_encode($reward) ?>);
            <?php endif; ?>
            jQuery('#magic-button').click(function () {
                var self = this;
                self.disabled = true;
                sendRequest('rotate', function (result) {
                    self.disabled = false;
                    showResult(result);
                });
            });
            result.find('button').click(function () {
                sendRequest(this.dataset.action, function (result) {
                    showRoulette(result);
                });
            });
        });
    }) ();
</script>
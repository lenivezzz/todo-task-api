<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->params['wwwHost'] . Url::to(['registration/confirm', 'token' => $user->confirmation_token]);

use yii\helpers\Url; ?>
Hello <?= $user->username ?>,

Follow the link below to verify your email:

<?= $verifyLink ?>

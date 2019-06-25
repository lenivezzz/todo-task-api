<?php
declare(strict_types=1);

namespace api\extensions\auth\events\handlers;

use api\extensions\auth\events\UserRegistered;
use RuntimeException;
use Yii;
use yii\base\BaseObject;

class UserRegisteredHandler extends BaseObject
{
    /**
     * @param UserRegistered $event
     */
    public function onUserRegistered(UserRegistered $event) : void
    {
        $sent = Yii::$app->mailer->compose(
            ['html' => 'emailVerify-html'],
            ['user' => $event->getUser()]
        )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name.' robot'])
            ->setTo($event->getUser()->email)
            ->setSubject('Account registration at '.Yii::$app->name)
            ->send();

        if (!$sent) {
            throw new RuntimeException(sprintf('Failed to send email to %s', $event->getUser()->email));
        }
    }
}

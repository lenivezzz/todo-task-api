<?php
declare(strict_types=1);

namespace api\extensions\auth\events\handlers;

use api\extensions\auth\events\UserRegistered;
use RuntimeException;
use Yii;
use yii\base\BaseObject;
use yii\mail\MailerInterface;

class UserRegisteredHandler extends BaseObject implements UserRegisteredHandlerInterface
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @param MailerInterface $mailer
     * @param array $config
     */
    public function __construct(MailerInterface $mailer, $config = [])
    {
        parent::__construct($config);
        $this->mailer = $mailer;
    }

    /**
     * @inheritDoc
     */
    public function onUserRegistered(UserRegistered $event) : void
    {
        $message = $this->mailer->compose(
            ['html' => 'emailVerify-html'],
            ['user' => $event->getUser()]
        )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name.' robot'])
            ->setTo($event->getUser()->email)
            ->setSubject('Account registration at '.Yii::$app->name);

        if (!$this->mailer->send($message)) {
            throw new RuntimeException(sprintf('Failed to send email to %s', $event->getUser()->email));
        }
    }
}

<?php
declare(strict_types=1);

namespace api\extensions\auth\events\handlers;

use api\extensions\auth\events\UserRegistered;
use RuntimeException;
use Yii;
use yii\base\BaseObject;

class UserRegisteredHandlerFileLog extends BaseObject implements UserRegisteredHandlerInterface
{
    private const FILE_PREFIX = 'user_registered_';

    /**
     * @var string
     */
    public $filePath;

    /**
     * @inheritDoc
     */
    public function onUserRegistered(UserRegistered $event) : void
    {
        $path = Yii::getAlias($this->filePath);
        if (!is_dir($path) && !mkdir($path, 0777, true) && !is_dir($path)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
        }

        file_put_contents($path . '/' . self::filename($event->getUser()->email), '');
    }

    /**
     * @param string $username
     * @return string
     */
    public static function filename(string $username) : string
    {
        return self::FILE_PREFIX . $username;
    }
}

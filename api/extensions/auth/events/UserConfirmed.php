<?php
declare(strict_types=1);

namespace api\extensions\auth\events;

use api\models\ApiUser;
use yii\base\Event;

class UserConfirmed extends Event
{
    /**
     * @var ApiUser
     */
    private $user;

    /**
     * @param ApiUser $user
     * @param array $config
     */
    public function __construct(ApiUser $user, $config = [])
    {
        parent::__construct($config);
        $this->user = $user;
    }

    /**
     * @return ApiUser
     */
    public function getUser() : ApiUser
    {
        return $this->user;
    }
}

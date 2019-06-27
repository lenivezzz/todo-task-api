<?php
declare(strict_types=1);

namespace api\events\handlers;

use api\extensions\auth\events\UserConfirmed;
use api\models\project\Project;
use yii\base\BaseObject;

class UserConfirmedHandler extends BaseObject
{
    /**
     * @param UserConfirmed $event
     */
    public function onUserConfirmed(UserConfirmed $event) : void
    {
        Project::createDefaultForUser($event->getUser());
    }
}

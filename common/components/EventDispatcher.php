<?php
declare(strict_types=1);

namespace common\components;

use yii\base\Component;
use yii\base\Event;

class EventDispatcher extends Component
{
    public function dispatch(Event $event) : void
    {
        $this->trigger(get_class($event), $event);
    }
}

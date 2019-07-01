<?php
declare(strict_types=1);

namespace api\fixtures;

use api\models\task\Task;
use yii\test\ActiveFixture;

class TaskFixture extends ActiveFixture
{
    public $modelClass = Task::class;
}

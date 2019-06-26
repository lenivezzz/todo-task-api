<?php
declare(strict_types=1);

namespace api\fixtures;

use api\models\project\Project;
use yii\test\ActiveFixture;

class ProjectFixture extends ActiveFixture
{
    public $modelClass = Project::class;
}

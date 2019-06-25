<?php
declare(strict_types=1);

namespace common\fixtures;

use yii\test\ActiveFixture;
use common\models\User;

class UserFixture extends ActiveFixture
{
    public $modelClass = User::class;
}
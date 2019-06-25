<?php
declare(strict_types=1);

namespace api\extensions\auth\fixtures;

use api\extensions\auth\models\Token;
use yii\test\ActiveFixture;

class TokenFixture extends ActiveFixture
{
    public $modelClass = Token::class;
}

<?php
declare(strict_types=1);

namespace api\extensions\auth\models;

use yii\base\Model;

class LoginForm extends Model
{
    /**
     * @var string
     */
    public $username;
    /**
     * @var string
     */
    public $password;

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            [['username', 'password'], 'required'],
        ];
    }
}

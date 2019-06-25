<?php
declare(strict_types=1);

namespace api\extensions\auth\models;

use api\models\ApiUser;
use yii\base\Model;

class RegistrationForm extends Model
{
    /**
     * @var string
     */
    public $email;
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
            ['username', 'trim'],
            ['email', 'trim'],
            [['email', 'username', 'password'], 'required'],
            ['password', 'string', 'min' => 8],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => ApiUser::class, 'targetAttribute' => 'email', 'skipOnError' => true],
        ];
    }
}

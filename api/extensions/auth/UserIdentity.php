<?php
declare(strict_types=1);

namespace api\extensions\auth;

use api\extensions\auth\models\Token;
use common\models\User;
use DateTime;
use yii\web\IdentityInterface;

class UserIdentity implements IdentityInterface
{
    /**
     * @var User
     */
    private $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        $user = User::findOne(['id' => $id, 'status' => User::STATUS_ACTIVE]);

        return $user ? new self($user) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $token = Token::find()
            ->with('user')
            ->where(['token' => $token])
            ->andWhere(['>', 'expires_at', (new DateTime())->format('Y-m-d H:i:s')])
            ->one();

        return ($token && $token->user->status === User::STATUS_ACTIVE) ? new self($token->user) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId() : int
    {
        return $this->user->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey() : string
    {
        return $this->user->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey) : bool
    {
        return $this->getAuthKey() === $authKey;
    }
}

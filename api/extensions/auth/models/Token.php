<?php
declare(strict_types=1);

namespace api\extensions\auth\models;

use common\models\User;
use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "token".
 *
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property string $expires_at
 *
 * @property User $user
 */
class Token extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName() : string
    {
        return 'token';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() : array
    {
        return [
            [['user_id', 'token', 'expires_at'], 'required'],
            [['user_id'], 'integer'],
            [['expires_at'], 'safe'],
            [['token'], 'string', 'max' => 255],
            [['token'], 'unique'],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() : array
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'token' => 'Token',
            'expires_at' => 'Expires At',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getUser() : ActiveQueryInterface
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return TokenQuery the active query used by this AR class.
     */
    public static function find() : ActiveQueryInterface
    {
        return new TokenQuery(static::class);
    }

    /**
     * @param $expire
     * @throws Exception
     */
    public function generateToken($expire) : void
    {
        $this->expires_at = $expire;
        $this->token = Yii::$app->security->generateRandomString();
    }
}

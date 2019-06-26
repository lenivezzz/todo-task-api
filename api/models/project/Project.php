<?php
declare(strict_types=1);

namespace api\models\project;

use api\models\ApiUser;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property string $title
 * @property int $user_id
 * @property int $status_id
 * @property int $is_default
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ApiUser $user
 */
class Project extends ActiveRecord
{
    public const STATUS_ACTIVE = 1;
    public const STATUS_ARCHIVED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName() : string
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() : array
    {
        return [
            [['title', 'user_id'], 'required'],
            ['status_id', 'default', 'value' => self::STATUS_ACTIVE],
            ['is_default', 'default', 'value' => 0],
            [['user_id', 'status_id', 'is_default'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 128],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ApiUser::class,
                'targetAttribute' => ['user_id' => 'id'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() : array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
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
            'title' => 'Title',
            'user_id' => 'User ID',
            'status_id' => 'Status ID',
            'is_default' => 'Is Default',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getUser() : ActiveQuery
    {
        return $this->hasOne(ApiUser::class, ['id' => 'user_id']);
    }

    /**
     * @return array
     */
    public function fields() : array
    {
        return [
            'id' => 'id',
            'title' => 'title',
            'is_default' => 'is_default',
            'status_id' => 'status_id',
        ];
    }
}

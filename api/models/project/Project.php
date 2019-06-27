<?php
declare(strict_types=1);

namespace api\models\project;

use api\exceptions\ProjectException;
use api\models\ApiUser;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\log\Logger;
use yii\validators\Validator;

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

    public const DEFAULT_PROJECT_TITLE = 'Incoming';

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
            ['title', 'trim'],
            [['title', 'user_id'], 'required'],
            ['status_id', 'default', 'value' => self::STATUS_ACTIVE],
            ['is_default', 'default', 'value' => 0],
            [['user_id', 'status_id', 'is_default'], 'integer'],
            [['title'], 'string', 'max' => 128],
            [
                'is_default',
                'validateChanges',
                'skipOnError' => true,
                'message' => 'Is not available to change attribute "is_default"',
            ],
            [
                'user_id',
                'exist',
                'skipOnError' => true,
                'targetClass' => ApiUser::class,
                'targetAttribute' => ['user_id' => 'id'],
            ],
            [
                'is_default',
                'validateUniqueDefault',
                'skipOnError' => true,
                'message' => 'User can have only one default project',
            ],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     * @param Validator $validator
     */
    public function validateUniqueDefault($attribute, $params, Validator $validator) : void
    {
        if ($this->isNewRecord && (int) $this->is_default === 0) {
            return;
        }

        $defaultProject = self::findOne([
            'user_id' => $this->user_id,
            'is_default' => 1,
        ]);

        if ($defaultProject && $defaultProject->id === $this->id) {
            return;
        }

        $defaultProject && (int) $this->is_default === 1  && $this->addError($attribute, $validator->message);
    }

    public function validateChanges($attribute, $params, Validator $validator) : void
    {
        if (!$this->isNewRecord && $this->isAttributeChanged($attribute)) {
            $this->addError($attribute, $validator->message);
        }
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

    /**
     * @param ApiUser $user
     * @return Project
     */
    public static function createDefaultForUser(ApiUser $user) : self
    {
        $model = new self();
        $model->setAttributes([
            'user_id' => $user->id,
            'is_default' => 1,
            'title' => self::DEFAULT_PROJECT_TITLE,
        ]);
        if (!$model->save()) {
            Yii::getLogger()->log(
                sprintf(
                    'Failed to save user default project. Errors: %s',
                    var_export($model->getErrors(), true)
                ),
                Logger::LEVEL_ERROR
            );
            throw new ProjectException('Failed to save default project');
        }

        return $model;
    }
}

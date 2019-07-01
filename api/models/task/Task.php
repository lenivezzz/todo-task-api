<?php
declare(strict_types=1);

namespace api\models\task;

use api\models\ApiUser;
use api\models\project\Project;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\Url;
use yii\web\Link;
use yii\web\Linkable;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $title
 * @property int $status_id
 * @property int $project_id
 * @property string $expires_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Project $project
 * @property ApiUser $user
 */
class Task extends ActiveRecord implements Linkable
{
    public const STATUS_ACTIVE = 1;
    public const STATUS_DONE = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName() : string
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() : array
    {
        return [
            [['title', 'project_id'], 'required'],
            ['status_id', 'default', 'value' => self::STATUS_ACTIVE],
            [['status_id', 'project_id'], 'integer'],
            [['expires_at', 'created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 256],
            [
                ['project_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Project::class,
                'targetAttribute' => ['project_id' => 'id'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() : array
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() : array
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'status_id' => 'Status ID',
            'project_id' => 'Project ID',
            'expires_at' => 'Expires At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getProject() : ActiveQuery
    {
        return $this->hasOne(Project::class, ['id' => 'project_id']);
    }

    public static function getStatusList() : array
    {
        return [
            self::STATUS_ACTIVE,
            self::STATUS_DONE,
        ];
    }

    /**
     * @inheritDoc
     */
    public function fields()
    {
        return [
            'id',
            'title',
            'status_id',
            'expires_at',
            'project_id',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['tasks/view', 'id' => $this->id], true),
        ];
    }
}

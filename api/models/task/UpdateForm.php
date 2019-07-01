<?php
declare(strict_types=1);

namespace api\models\task;

use yii\base\Model;

class UpdateForm extends Model
{
    /**
     * @var string
     */
    public $title;
    /**
     * @var int
     */
    public $status_id;
    /**
     * @var int
     */
    public $project_id;
    /**
     * @var string
     */
    public $expires_at;

    /**
     * @var array
     */
    private $projectIdList;

    public function __construct(array $projectIdList, $config = [])
    {
        parent::__construct($config);
        $this->projectIdList = $projectIdList;
    }

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['title', 'trim'],
            [['title', 'project_id', 'status_id'], 'required'],
            ['title', 'string', 'max' => 256],
            ['status_id', 'in', 'range' => self::statusList()],
            ['project_id', 'in', 'range' => $this->projectIdList],
            [
                'expires_at',
                'required',
                'isEmpty' => static function ($value) {
                    return $value === '';
                },
            ],
            ['expires_at', 'datetime', 'format' => 'php:Y-m-d H:i:s'],
        ];
    }

    /**
     * @return array
     */
    public static function statusList() : array
    {
        return [
            Task::STATUS_ACTIVE,
            Task::STATUS_DONE,
        ];
    }
}

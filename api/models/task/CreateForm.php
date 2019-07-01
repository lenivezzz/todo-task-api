<?php
declare(strict_types=1);

namespace api\models\task;

use yii\base\Model;

class CreateForm extends Model
{
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $expires_at;
    /**
     * @var integer
     */
    public $project_id;

    /**
     * @var array
     */
    private $projectIdList;

    /**
     * @param array $projectIdList
     * @param array $config
     */
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
            [['title', 'project_id'], 'required'],
            ['title', 'string', 'max' => 256],
            ['project_id', 'integer'],
            ['project_id', 'in', 'range' => $this->projectIdList],
            ['expires_at', 'datetime', 'format' => 'php:Y-m-d H:i:s'],
        ];
    }
}

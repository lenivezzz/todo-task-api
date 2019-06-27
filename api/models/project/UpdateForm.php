<?php
declare(strict_types=1);

namespace api\models\project;

use yii\base\Model;

class UpdateForm extends Model
{
    /**
     * @var string|null
     */
    public $title;
    /**
     * @var int|null
     */
    public $status_id;

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['title', 'trim'],
            ['status_id', 'in', 'range' => self::statusList()],
            ['title', 'string', 'max' => 128, 'min' => 1],
        ];
    }

    /**
     * @return array
     */
    public static function statusList() : array
    {
        return [
            Project::STATUS_ACTIVE,
            Project::STATUS_ARCHIVED,
        ];
    }
}

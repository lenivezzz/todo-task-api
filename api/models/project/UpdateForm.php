<?php
declare(strict_types=1);

namespace api\models\project;

use yii\base\Model;

class UpdateForm extends Model
{
    public $title;
    public $statusId;

    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public function rules() : array
    {
        return [
            ['title', 'trim'],
            ['title', 'required'],
            ['statusId', 'in', 'range' => self::statusList()],
            ['title', 'string', 'max' => 128],
        ];
    }

    public static function statusList() : array
    {
        return [
            Project::STATUS_ACTIVE,
            Project::STATUS_ARCHIVED,
        ];
    }
}

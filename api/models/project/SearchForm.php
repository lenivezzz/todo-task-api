<?php
declare(strict_types=1);

namespace api\models\project;

use yii\base\Model;

class SearchForm extends Model
{
    /**
     * @var integer
     */
    public $statusId;

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['statusId', 'trim'],
            ['statusId', 'in', 'range' => self::statusList()],
        ];
    }

    public static function statusList() : array
    {
        return [
            Project::STATUS_ACTIVE,
            Project::STATUS_ARCHIVED,
        ];
    }

    public function formName() : string
    {
        return 's';
    }
}

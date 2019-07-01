<?php
declare(strict_types=1);

namespace api\models\project;

use yii\base\Model;

class SearchForm extends Model
{
    /**
     * @var integer
     */
    public $status_id;

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['status_id', 'trim'],
            ['status_id', 'in', 'range' => self::statusList()],
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

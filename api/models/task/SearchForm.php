<?php
declare(strict_types=1);

namespace api\models\task;

use yii\base\Model;

class SearchForm extends Model
{
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $status_id;
    /**
     * @var string
     */
    public $expires_at_start;
    /**
     * @var string
     */
    public $expires_at_end;

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            [['title', 'status_id', 'expires_at_start', 'expires_at_end'],  'trim'],
            [['status_id'], 'match', 'pattern' => '/^\d+(,\d+)*$/'],
            ['expires_at_start', 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            ['expires_at_end', 'datetime', 'format' => 'php:Y-m-d H:i:s'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function formName() : string
    {
        return 's';
    }
}

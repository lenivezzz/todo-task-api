<?php
declare(strict_types=1);

namespace api\models\project;

use yii\base\Model;

class CreateForm extends Model
{
    /**
     * @var string|null
     */
    public $title;

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            ['title', 'trim'],
            ['title', 'required'],
            ['title', 'length', 'max' => 128],
        ];
    }
}

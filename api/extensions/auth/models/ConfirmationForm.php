<?php
declare(strict_types=1);

namespace api\extensions\auth\models;

use yii\base\Model;

class ConfirmationForm extends Model
{
    /**
     * @var string
     */
    public $confirmationToken;

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['confirmationToken', 'trim'],
            ['confirmationToken', 'required'],
        ];
    }
}

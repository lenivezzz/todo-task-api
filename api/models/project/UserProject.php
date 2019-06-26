<?php
declare(strict_types=1);

namespace api\models\project;

use Yii;

/**
 * @package api\models\project
 */
class UserProject extends Project
{
    /**
     * @return bool
     */
    public function beforeValidate() : bool
    {
        if (!parent::beforeValidate()) {
            return false;
        }

        if ($this->isNewRecord && !$this->user_id) {
            $this->user_id = Yii::$app->user->identity->getId();
        }

        return true;
    }
}

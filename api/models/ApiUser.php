<?php
declare(strict_types=1);

namespace api\models;

use common\models\User;

class ApiUser extends User
{
    /**
     * @return array
     */
    public function fields() : array
    {
        return [
            'id' => 'id',
            'username' => 'username',
            'email' => 'email',
            'status' => 'status'
        ];
    }
}

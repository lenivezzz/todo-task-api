<?php
declare(strict_types=1);

namespace api\controllers;

use yii\filters\auth\HttpBearerAuth;

abstract class AbstractBearerAwareActiveController extends AbstractActiveController
{
    /**
     * @inheritDoc
     */
    public function behaviors() : array
    {
        $behaviors =  parent::behaviors();

        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class
        ];
        return $behaviors;
    }
}

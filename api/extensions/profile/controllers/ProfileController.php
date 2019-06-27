<?php
declare(strict_types=1);

namespace api\extensions\profile\controllers;

use api\models\ApiUser;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;

class ProfileController extends Controller
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

    /**
     * @inheritDoc
     */
    protected function verbs() : array
    {
        return [
            'index' => ['get'],
        ];
    }

    /**
     * @return ApiUser|null
     */
    public function actionIndex()
    {
        return ApiUser::findOne(['id' => Yii::$app->getUser()->id]);
    }
}

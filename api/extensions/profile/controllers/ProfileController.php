<?php
declare(strict_types=1);

namespace api\extensions\profile\controllers;

use api\models\ApiUser;
use Yii;
use yii\filters\AccessControl;
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

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];

        return $behaviors;
    }

    /**
     * @inheritDoc
     */
    protected function verbs() : array
    {
        return array_merge(parent::verbs(), [
            'profile' => ['get'],
        ]);
    }

    /**
     * @return ApiUser|null
     */
    public function actionIndex()
    {
        return ApiUser::findOne(['id' => Yii::$app->getUser()->id]);
    }
}

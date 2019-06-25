<?php
declare(strict_types=1);

namespace api\extensions\auth\controllers;

use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;

class LogoutController extends Controller
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
            'only' => ['ping', 'index'],
            'rules' => [
                [
                    'actions' => ['index', 'ping'],
                    'allow' => true,
                    'roles' => ['@'],
                ]
            ],
        ];

        return $behaviors;
    }

    /**
     * @return array
     */
    protected function verbs() : array
    {
        return [
            'index' => ['post'],
        ];
    }

    public function actionIndex()
    {
    }

    public function actionPing()
    {
        return [];
    }
}

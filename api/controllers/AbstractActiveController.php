<?php
declare(strict_types=1);

namespace api\controllers;

use api\controllers\actions\CreateAction;
use api\controllers\actions\DeleteAction;
use api\controllers\actions\IndexAction;
use api\controllers\actions\OptionsAction;
use api\controllers\actions\UpdateAction;
use api\controllers\actions\ViewAction;
use yii\rest\ActiveController;

abstract class AbstractActiveController extends ActiveController
{
    /**
     * @inheritDoc
     */
    public function actions() : array
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'view' => [
                'class' => ViewAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'create' => [
                'class' => CreateAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->createScenario,
            ],
            'update' => [
                'class' => UpdateAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->updateScenario,
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'options' => [
                'class' => OptionsAction::class,
            ],
        ];
    }
}

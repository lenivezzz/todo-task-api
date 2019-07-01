<?php
declare(strict_types=1);

namespace api\controllers\actions;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

class CreateAction extends \yii\rest\CreateAction
{
    /**
     * @var string|Model|null
     */
    public $formClass;

    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /* @var $model ActiveRecord */
        $model = new $this->modelClass([
            'scenario' => $this->scenario,
        ]);
        if ($this->formClass) {
            /** @var Model $form */
            $form = is_callable($this->formClass) ? call_user_func($this->formClass) : new $this->formClass();
            $form->load(Yii::$app->getRequest()->bodyParams, '');
            if (!$form->validate()) {
                return $form;
            }
            $model->setAttributes($form->toArray());
        } else {
            $model->load(Yii::$app->getRequest()->bodyParams, '');
        }

        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute([$this->viewAction, 'id' => $id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $model;
    }
}

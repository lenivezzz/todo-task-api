<?php
declare(strict_types=1);

namespace api\controllers\actions;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\ActiveRecordInterface;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class UpdateAction extends \yii\rest\UpdateAction
{
    /**
     * @var Model|null
     */
    public $formClass;

    /**
     * @param string $id
     * @return Model|ActiveRecord|ActiveRecordInterface
     * @throws ServerErrorHttpException
     * @throws NotFoundHttpException
     */
    public function run($id)
    {
        /* @var $model ActiveRecord */
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }
        $model->scenario = $this->scenario;

        if ($this->formClass) {
            /** @var Model $form */
            $form = is_callable($this->formClass) ? call_user_func($this->formClass) : new $this->formClass();
            $form->load($model->toArray(), '');
            $form->load(Yii::$app->getRequest()->bodyParams, '');
            if (!$form->validate()) {
                return $form;
            }
            $model->setAttributes($form->toArray());
        } else {
            $model->load(Yii::$app->getRequest()->bodyParams, '');
        }

        if ($model->save() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }

        return $model;
    }
}

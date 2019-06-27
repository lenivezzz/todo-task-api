<?php
declare(strict_types=1);

namespace api\controllers;

use api\models\project\Project;
use api\models\project\SearchForm;
use api\models\project\UpdateForm;
use api\models\project\UserProject;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class UserprojectsController extends ActiveController
{
    public $modelClass = UserProject::class;

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

    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        $actions['view']['findModel'] = [$this, 'findModel'];
        unset($actions['update']);
        return $actions;
    }

    /**
     * @param $id
     * @return Project|UpdateForm
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->is_default === 1) {
            throw new ForbiddenHttpException('You are not allowed to update default project.');
        }
        $form = new UpdateForm();
        $form->load(Yii::$app->getRequest()->bodyParams, '');
        if (!$form->validate()) {
            return $form;
        }
        $model->setAttributes(['title' => $form->title]);
        $form->statusId && $model->setAttribute('status_id', $form->statusId);
        if ($model->save() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }

        return $model;
    }

    /**
     * @return ActiveDataProvider
     * @throws BadRequestHttpException
     */
    public function prepareDataProvider() : ActiveDataProvider
    {
        $form = new SearchForm();
        $form->load(Yii::$app->request->queryParams);
        if (!$form->validate()) {
            throw new BadRequestHttpException($form->getFirstErrors()['message']);
        }

        $query = Project::find()->where(['user_id' => Yii::$app->user->identity->getId()])
            ->andFilterWhere(['status_id' => $form->statusId]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

    /**
     * @param $id
     * @return Project
     * @throws NotFoundHttpException
     */
    public function findModel($id) : Project
    {
        $model = Project::findOne([
            'user_id' => Yii::$app->user->identity->getId(),
            'id' => $id,
        ]);

        if (!$model) {
            throw new NotFoundHttpException("Object not found: $id");
        }

        return $model;
    }
}

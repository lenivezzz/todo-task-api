<?php
declare(strict_types=1);

namespace api\controllers;

use api\models\project\CreateForm;
use api\models\project\Project;
use api\models\project\SearchForm;
use api\models\project\UpdateForm;
use api\models\project\UserProject;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\ReplaceArrayValue;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;

class ProjectsController extends AbstractBearerAwareActiveController
{
    public $modelClass = UserProject::class;

    /**
     * @inheritDoc
     */
    public function actions() : array
    {
        return ArrayHelper::merge(parent::actions(), [
            'index' => [
                'prepareDataProvider' => new ReplaceArrayValue([$this, 'prepareDataProvider']),
            ],
            'create' => [
                'formClass' => CreateForm::class,
            ],
            'update' => [
                'checkAccess' => new ReplaceArrayValue([$this, 'checkUpdateAccess']),
                'formClass' => UpdateForm::class,
            ]
        ]);
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
            ->andFilterWhere(['status_id' => $form->status_id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

    /**
     * @param string $action
     * @param Project|null $model
     * @param array $params
     * @return bool
     * @throws ForbiddenHttpException
     */
    public function checkAccess($action, $model = null, $params = []) : bool
    {
        if ($model && $model->user_id !== Yii::$app->user->identity->getId()) {
            throw new ForbiddenHttpException('You do not have access to do that.');
        }
        return true;
    }

    /**
     * @param $action
     * @param Project $model
     * @param $params
     * @return bool
     * @throws ForbiddenHttpException
     */
    public function checkUpdateAccess($action, $model, $params = []) : bool
    {
        if (!$this->checkAccess($action, $model, $params = [])) {
            return false;
        }

        if ($model->is_default === 1) {
            throw new ForbiddenHttpException('You are not allowed to update default project.');
        }
        return true;
    }
}

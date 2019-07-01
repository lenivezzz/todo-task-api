<?php
declare(strict_types=1);

namespace api\controllers;

use api\models\project\Project;
use api\models\task\CreateForm;
use api\models\task\SearchForm;
use api\models\task\Task;
use api\models\task\UpdateForm;
use LogicException;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\ReplaceArrayValue;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;

/**
 *
 * @property array $userActiveProjectIdList
 * @property null|string $requestProjectId
 */
class TasksController extends AbstractBearerAwareActiveController
{
    public $modelClass = Task::class;

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
                'formClass' => function () {
                    $form = new CreateForm($this->getUserActiveProjectIdList());
                    $form->project_id = $this->getRequestProjectId();
                    return $form;
                },
            ],
            'view' => [
                'checkAccess' => new ReplaceArrayValue([$this, 'checkAccess']),
            ],
            'delete' => [
                'checkAccess' => new ReplaceArrayValue([$this, 'checkAccess']),
            ],
            'update' => [
                'checkAccess' => new ReplaceArrayValue([$this, 'checkAccess']),
                'formClass' => function () {
                    return new UpdateForm($this->getUserActiveProjectIdList());
                },
            ]
        ]);
    }

    /**
     * @return ActiveDataProvider
     * @throws BadRequestHttpException
     */
    public function prepareDataProvider() : ActiveDataProvider
    {
        $query = Task::find()->where([
            'project_id' => $this->getUserActiveProjectIdList(),
        ]);

        if ($projectId = $this->getRequestProjectId()) {
            $query->andWhere(['project_id' => $projectId]);
        }

        $search = new SearchForm();
        $search->load(Yii::$app->request->queryParams);

        if (!$search->validate()) {
            throw new BadRequestHttpException(current($search->getFirstErrors()));
        }

        $query->andFilterWhere(['like', 'title', $search->title]);
        $search->status_id && $query->andFilterWhere(['in', 'status_id', explode(',', $search->status_id)]);
        $query->andFilterWhere(['>=', 'expires_at', $search->expires_at_start]);
        $query->andFilterWhere(['<=', 'expires_at', $search->expires_at_end]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

    /**
     * @inheritDoc
     * @param $model Task|null
     */
    public function checkAccess($action, $model = null, $params = []) : bool
    {
        $projectId = $this->getRequestProjectId();
        if ($projectId !== null && !in_array((int) $projectId, $this->getUserActiveProjectIdList(), true)) {
            throw new ForbiddenHttpException();
        }
        if ($model !== null && !in_array((int) $model->project_id, $this->getUserActiveProjectIdList(), true)) {
            throw new ForbiddenHttpException();
        }

        return true;
    }

    /**
     * @return array
     */
    private function getUserActiveProjectIdList() : array
    {
        $projectList = Project::findAll([
            'user_id' => Yii::$app->user->identity->getId(),
            'status_id' => Project::STATUS_ACTIVE
        ]);
        if (!$projectList) {
            throw new LogicException('Current user doesn\'t have projects');
        }

        return array_column($projectList, 'id');
    }

    /**
     * @return string|null
     */
    private function getRequestProjectId() : ?string
    {
        return Yii::$app->request->getQueryParam('projectId');
    }
}

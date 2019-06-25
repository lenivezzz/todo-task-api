<?php
declare(strict_types=1);

namespace www\controllers;

use yii\web\Controller;
use yii\web\ErrorAction;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }
}

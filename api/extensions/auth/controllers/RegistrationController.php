<?php
declare(strict_types=1);

namespace api\extensions\auth\controllers;

use api\extensions\auth\events\UserConfirmed;
use api\extensions\auth\events\UserRegistered;
use api\extensions\auth\exceptions\FailedToRegisterUserException;
use api\extensions\auth\exceptions\FailedToVerifyUserException;
use api\extensions\auth\exceptions\UserNotFoundException;
use api\extensions\auth\models\ConfirmationForm;
use api\extensions\auth\models\RegistrationForm;
use api\extensions\auth\RegistrationInterface;
use api\models\ApiUser;
use Yii;
use yii\base\InvalidConfigException;
use yii\filters\AccessControl;
use yii\rest\Controller;
use yii\web\HttpException;

class RegistrationController extends Controller
{
    /**
     * @var RegistrationInterface
     */
    private $registration;

    /**
     * @param $id
     * @param $module
     * @param RegistrationInterface $registration
     * @param array $config
     */
    public function __construct($id, $module, RegistrationInterface $registration, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->registration = $registration;
    }

    /**
     * @inheritDoc
     */
    public function behaviors() : array
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => [ 'index', 'confirm'],
            'rules' => [
                [
                    'actions' => ['index', 'confirm'],
                    'allow' => true,
                    'roles' => ['?'],
                ]
            ],
        ];
        return $behaviors;
    }

    /**
     * @inheritDoc
     */
    public function verbs() : array
    {
        return [
            'index' => ['post'],
            'confirm' => ['post'],
        ];
    }

    /**
     * @return RegistrationForm|ApiUser
     * @throws InvalidConfigException
     * @throws HttpException
     */
    public function actionIndex()
    {
        $form = new RegistrationForm();
        $form->load(Yii::$app->request->getBodyParams(), '');
        if (!$form->validate()) {
            return $form;
        }

        try {
            $user = $this->registration->register([
                'email' => $form->email,
                'username' => $form->username,
                'password' => $form->password,
            ]);
        } catch (FailedToRegisterUserException $e) {
            throw new HttpException(500, 'Failed to register user', 0, $e);
        }
        Yii::$app->eventDispatcher->dispatch(new UserRegistered($user));
        Yii::$app->response->setStatusCode(201);
        return $user;
    }

    /**
     * @return ConfirmationForm|array
     * @throws HttpException
     * @throws InvalidConfigException
     */
    public function actionConfirm()
    {
        $form = new ConfirmationForm();
        $form->load(Yii::$app->request->getBodyParams(), '');
        if (!$form->validate()) {
            return $form;
        }
        try {
            $confirmedUser = $this->registration->confirm($form->confirmationToken);
            Yii::$app->eventDispatcher->dispatch(new UserConfirmed($confirmedUser));
        } catch (UserNotFoundException $e) {
            $form->addError('confirmationToken', 'Token not found or already expired');
            return $form;
        } catch (FailedToVerifyUserException $e) {
            throw new HttpException(500, 'Failed to verify user');
        }
        Yii::$app->response->setStatusCode(204);
        return [];
    }
}

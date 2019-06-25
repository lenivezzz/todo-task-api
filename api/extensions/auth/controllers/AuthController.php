<?php
declare(strict_types=1);

namespace api\extensions\auth\controllers;

use api\extensions\auth\AuthInterface;
use api\extensions\auth\exceptions\InvalidCredentialsException;
use api\extensions\auth\exceptions\FailedToSaveTokenException;
use api\extensions\auth\exceptions\UserNotFoundException;
use api\extensions\auth\models\LoginForm;
use InvalidArgumentException;
use Yii;
use yii\base\Exception;
use yii\log\Logger;
use yii\rest\Controller;
use yii\web\ServerErrorHttpException;

class AuthController extends Controller
{
    private $authManager;

    /**
     * @param $id
     * @param $module
     * @param AuthInterface $authManager
     * @param array $config
     */
    public function __construct($id, $module, AuthInterface $authManager, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->authManager = $authManager;
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

    /**
     * @return LoginForm|array
     * @throws ServerErrorHttpException
     * @throws Exception
     */
    public function actionIndex()
    {
        $loginForm = new LoginForm();
        $loginForm->load(Yii::$app->request->bodyParams, '');
        if (!$loginForm->validate()) {
            return $loginForm;
        }
        try {
            return $this->authManager->authenticate([
                'username' => $loginForm->username,
                'password' => $loginForm->password,
            ])->getAttributes(['token', 'expires_at']);
        } catch (UserNotFoundException | InvalidCredentialsException $e) {
            $loginForm->addError('password', 'Incorrect username or password.');
            return $loginForm;
        } catch (FailedToSaveTokenException | InvalidArgumentException $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            throw new ServerErrorHttpException('Failed to authenticate user');
        }
    }
}

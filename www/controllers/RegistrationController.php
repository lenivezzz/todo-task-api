<?php
declare(strict_types=1);

namespace www\controllers;

use api\extensions\auth\RegistrationInterface;
use www\extensions\api\exceptions\TodokeeperRequestException;
use www\extensions\api\exceptions\TodokeeperRuntimeException;
use www\extensions\api\exceptions\UnexpectedResponseStatusException;
use www\extensions\api\TodokeeperInterface;
use yii\web\Controller;
use yii\web\HttpException;

class RegistrationController extends Controller
{
    /**
     * @var RegistrationInterface
     */
    private $todokeeper;

    /**
     * @param $id
     * @param $module
     * @param TodokeeperInterface $todokeeper
     * @param array $config
     */
    public function __construct($id, $module, TodokeeperInterface $todokeeper, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->todokeeper = $todokeeper;
    }

    /**
     * @param string $token
     * @return string
     * @throws HttpException
     */
    public function actionConfirm(string $token)
    {
        try {
            $this->todokeeper->confirm($token);
        } catch (TodokeeperRequestException $e) {
            throw new HttpException(404, $e->getMessage(), 0, $e);
        } catch (TodokeeperRuntimeException | UnexpectedResponseStatusException $e) {
            throw new HttpException(500, 'Failed to confirm user. Please try again later.');
        }

        return $this->redirect(['confirmed']);
    }

    /**
     * @return string
     */
    public function actionConfirmed()
    {
        return $this->render('confirmed');
    }
}

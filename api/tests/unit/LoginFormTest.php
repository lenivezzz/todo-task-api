<?php
declare(strict_types=1);

namespace api\tests\unit;

use api\extensions\auth\models\LoginForm;
use Codeception\Test\Unit;

class LoginFormTest extends Unit
{

    public function testValidation() : void
    {
        $form = new LoginForm();

        $this->assertFalse($form->validate());

        $form = new LoginForm();
        $form->setAttributes(['username' => 'user']);
        $this->assertFalse($form->validate());

        $form = new LoginForm();
        $form->setAttributes(['password' => 'user']);
        $this->assertFalse($form->validate());

        $form = new LoginForm();
        $form->setAttributes(['username' => 'user', 'password' => 'password']);
        $this->assertTrue($form->validate());
    }
}

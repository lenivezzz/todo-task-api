<?php
declare(strict_types=1);

namespace api\tests\unit;

use api\extensions\auth\models\RegistrationForm;
use Codeception\Test\Unit;
use common\fixtures\UserFixture;

class RegistrationFormTest extends Unit
{
    public function _fixtures() : array
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php',
            ]
        ];
    }

    public function testValidation() : void
    {
        $form = new RegistrationForm();
        $this->assertFalse($form->validate());

        $this->assertFalse($form->validate(['email']));
        $form->email = 'wrongemailformat';
        $this->assertFalse($form->validate(['email']));
//        unique email
        $form->email = 'test2@mail.com';
        $this->assertFalse($form->validate(['email']));
        $form->email = 'correctemail@todoemail.com';
        $this->assertTrue($form->validate(['email']));

        $this->assertFalse($form->validate(['username']));
        $form->username = '    ';
        $this->assertFalse($form->validate(['username']));
        $form->username = 'username';
        $this->assertTrue($form->validate(['username']));

        $this->assertFalse($form->validate(['password']));
        $form->password = '1234567';
        $this->assertFalse($form->validate(['password']));
        $form->password = '12345678';
        $this->assertTrue($form->validate(['password']));
    }
}

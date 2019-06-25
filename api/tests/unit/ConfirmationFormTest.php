<?php
declare(strict_types=1);

namespace api\tests\unit;

use api\extensions\auth\models\ConfirmationForm;
use Codeception\Test\Unit;

class ConfirmationFormTest extends Unit
{
    public function testValidation() : void
    {
        $form = new ConfirmationForm();
        $this->assertFalse($form->validate());
        $form->confirmationToken = '   ';
        $this->assertFalse($form->validate(['confirmationToken']));
        $form->confirmationToken = 'confirmationtoken';
        $this->assertTrue($form->validate());
    }
}

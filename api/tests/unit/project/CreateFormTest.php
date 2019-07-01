<?php
declare(strict_types=1);

namespace api\tests\unit\project;

use api\models\project\CreateForm;
use Codeception\Test\Unit;

class CreateFormTest extends Unit
{

    public function testValidate() : void
    {
        $model = new CreateForm();
        $this->assertFalse($model->validate());

        $model->title = str_repeat('a', 129);
        $this->assertFalse($model->validate(['title']));

        $model->title = str_repeat('a', 128) . '   ';
        $this->assertTrue($model->validate(['title']));

        $model->title = str_repeat('a', 128);
        $this->assertTrue($model->validate(['title']));

        $this->assertTrue($model->validate());
    }
}

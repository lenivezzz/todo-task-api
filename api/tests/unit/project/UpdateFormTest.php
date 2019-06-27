<?php
declare(strict_types=1);

namespace api\tests\unit\project;

use api\models\project\UpdateForm;
use Codeception\Test\Unit;

class UpdateFormTest extends Unit
{
    public function testValidate() : void
    {
        $model = new UpdateForm();
        $this->assertFalse($model->validate());

        $model = new UpdateForm();
        $model->title = str_repeat('a', 129);
        $this->assertFalse($model->validate(['title']));

        $model = new UpdateForm();
        $model->title = str_repeat('a', 128) . '   ';
        $this->assertTrue($model->validate(['title']));

        $model = new UpdateForm();
        $model->title = str_repeat('a', 128);
        $this->assertTrue($model->validate(['title']));

        $model->statusId = 0;
        $this->assertFalse($model->validate(['statusId']));

        $model->statusId = 1;
        $this->assertTrue($model->validate(['statusId']));

        $this->assertTrue($model->validate());
    }
}

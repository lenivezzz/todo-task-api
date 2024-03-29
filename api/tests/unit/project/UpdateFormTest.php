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
        $this->assertTrue($model->validate());

        $model = new UpdateForm();
        $model->title = str_repeat('a', 129);
        $this->assertFalse($model->validate(['title']));

        $model = new UpdateForm();
        $model->title = str_repeat('a', 128) . '   ';
        $this->assertTrue($model->validate(['title']));

        $model = new UpdateForm();
        $model->title = str_repeat('a', 128);
        $this->assertTrue($model->validate(['title']));

        $model->status_id = 0;
        $this->assertFalse($model->validate(['status_id']));

        $model->status_id = 1;
        $this->assertTrue($model->validate(['status_id']));

        $this->assertTrue($model->validate());
    }
}

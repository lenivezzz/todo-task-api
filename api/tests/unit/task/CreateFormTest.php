<?php
declare(strict_types=1);

namespace api\tests\unit\task;

use api\models\task\CreateForm;
use Codeception\Test\Unit;

class CreateFormTest extends Unit
{
    public function testValidate() : void
    {
        $model = new CreateForm([1, 2]);
        $this->assertFalse($model->validate());
        $model->title = '';
        $this->assertFalse($model->validate(['title']));
        $model->title = '   ';
        $this->assertFalse($model->validate(['title']));
        $model->title = str_repeat('a', 257);
        $this->assertFalse($model->validate(['title']));
        $model->title = str_repeat('a', 256);
        $this->assertTrue($model->validate(['title']));


        $model->project_id = '';
        $this->assertFalse($model->validate(['project_id']));
        $model->project_id = 5;
        $this->assertFalse($model->validate(['project_id']));
        $model->project_id = 'string';
        $this->assertFalse($model->validate(['project_id']));
        $model->project_id = 1;
        $this->assertTrue($model->validate(['project_id']));


        $this->assertTrue($model->validate(['expires_at']));
        $model->expires_at = 'string';
        $this->assertFalse($model->validate(['expires_at']));
        $model->expires_at = '2017-10-23 16:34:00';
        $this->assertTrue($model->validate(['expires_at']));

        $this->assertTrue($model->validate());
    }

    public function testToArray() : void
    {
        $model = new CreateForm([1, 2]);
        $attributes = [
            'expires_at' => '2017-10-23 16:34:00',
            'project_id' => 1,
            'title' => 'title',
        ];
        $model->setAttributes($attributes);
        $this->assertEquals($attributes, $model->toArray());
    }
}

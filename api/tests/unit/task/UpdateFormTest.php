<?php
declare(strict_types=1);

namespace api\tests\unit\task;

use api\models\task\UpdateForm;
use Codeception\Test\Unit;

class UpdateFormTest extends Unit
{
    public function testValidate() : void
    {
        $model = new UpdateForm([1, 2]);
        $this->assertFalse($model->validate());

        $this->assertFalse($model->validate(['title']));
        $model->title = '';
        $this->assertFalse($model->validate(['title']));
        $model->title = str_repeat('a', 257);
        $this->assertFalse($model->validate(['title']));
        $model->title = str_repeat('a', 256);
        $this->assertTrue($model->validate(['title']));
        $model->title = str_repeat('a', 256) . '  ';
        $this->assertTrue($model->validate(['title']));

        $this->assertFalse($model->validate(['status_id']));
        $model->status_id = '';
        $this->assertFalse($model->validate(['status_id']));
        $model->status_id = 5;
        $this->assertFalse($model->validate(['status_id']));
        $model->status_id = 1;
        $this->assertTrue($model->validate(['status_id']));

        $this->assertFalse($model->validate(['project_id']));
        $model->project_id = '';
        $this->assertFalse($model->validate(['project_id']));
        $model->project_id = 'string';
        $this->assertFalse($model->validate(['project_id']));
        $model->project_id = 0;
        $this->assertFalse($model->validate(['project_id']));
        $model->project_id = 1;
        $this->assertTrue($model->validate(['project_id']));

        $this->assertTrue($model->validate(['expires_at']));
        $model->expires_at = '';
        $this->assertFalse($model->validate(['expires_at']));
        $model->expires_at = date('Y/d/m H:i:s', time() + 3655);
        $this->assertFalse($model->validate(['expires_at']));
        $model->expires_at = date('Y-m-d H:i:s', time() + 3655);
        $this->assertTrue($model->validate(['expires_at']));

        $this->assertTrue($model->validate());
    }

    public function testToArray() : void
    {
        $model = new UpdateForm([1, 2]);
        $expiresAt = date('Y-m-d H:i:s', time() + 3655);
        $attributes = [
            'expires_at' => $expiresAt,
            'project_id' => 1,
            'title' => 'title',
            'status_id' => 1,
        ];
        $model->setAttributes($attributes);
        $this->assertEquals($attributes, $model->toArray());

        $model = new UpdateForm([1, 2]);
        $model->load($attributes, '');
        $model->load(['expires_at' => null], '');
        $this->assertNotEquals($attributes, $model->toArray());
        $this->assertEquals([
            'expires_at' => null,
            'project_id' => 1,
            'title' => 'title',
            'status_id' => 1,
        ], $model->toArray());
    }
}

<?php
declare(strict_types=1);

namespace api\tests\unit\task;

use api\models\task\SearchForm;
use Codeception\Test\Unit;

class SearchFormTest extends Unit
{
    public function testValidate() : void
    {
        $model = new SearchForm();
        $this->assertTrue($model->validate());

        $model->title = '';
        $this->assertTrue($model->validate(['title']));
        $model->title = '  ';
        $this->assertTrue($model->validate(['title']));
        $model->title = 'str';
        $this->assertTrue($model->validate(['title']));

        $model->status_id = '';
        $this->assertTrue($model->validate(['status_id']));
        $model->status_id = '1.2,3,';
        $this->assertFalse($model->validate(['status_id']));
        $model->status_id = '1 3,';
        $this->assertFalse($model->validate(['status_id']));
        $model->status_id = '1,,3,';
        $this->assertFalse($model->validate(['status_id']));
        $model->status_id = 1;
        $this->assertTrue($model->validate(['status_id']));
        $model->status_id = '1,2';
        $this->assertTrue($model->validate(['status_id']));

        $model->expires_at_start = '';
        $this->assertTrue($model->validate(['expires_at_start']));
        $model->expires_at_start = 'string';
        $this->assertFalse($model->validate(['expires_at_start']));
        $model->expires_at_start = date('Y/m/d H:i:s');
        $this->assertFalse($model->validate(['expires_at_start']));
        $model->expires_at_start = date('Y-m-d H:i:s');
        $this->assertTrue($model->validate(['expires_at_start']));

        $model->expires_at_end = '';
        $this->assertTrue($model->validate(['expires_at_end']));
        $model->expires_at_end = 'string';
        $this->assertFalse($model->validate(['expires_at_end']));
        $model->expires_at_end = date('Y/m/d H:i:s');
        $this->assertFalse($model->validate(['expires_at_end']));
        $model->expires_at_end = date('Y-m-d H:i:s');
        $this->assertTrue($model->validate(['expires_at_end']));

        $this->assertTrue($model->validate());
    }
}

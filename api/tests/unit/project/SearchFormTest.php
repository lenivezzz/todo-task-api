<?php
declare(strict_types=1);

namespace api\tests\unit\project;

use api\models\project\SearchForm;
use Codeception\Test\Unit;

class SearchFormTest extends Unit
{
    public function testValidate() : void
    {
        $model = new SearchForm();
        $this->assertTrue($model->validate());

        $model = new SearchForm();
        $model->statusId = 0;
        $this->assertFalse($model->validate());

        $model = new SearchForm();
        $model->statusId = 1;
        $this->assertTrue($model->validate());

        $model = new SearchForm();
        $model->statusId = ' 1';
        $this->assertTrue($model->validate());
        $this->assertEquals('1', $model->statusId);
    }
}

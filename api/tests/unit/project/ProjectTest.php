<?php
declare(strict_types=1);

namespace api\tests\unit\project;

use api\fixtures\ProjectFixture;
use api\models\project\Project;
use Codeception\Test\Unit;
use common\fixtures\UserFixture;

class ProjectTest extends Unit
{
    public function _fixtures() : array
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php',
            ],
            'project' => [
                'class' => ProjectFixture::class,
                'dataFile' => codecept_data_dir() . 'project.php',
            ],
        ];
    }

    public function testValidateDefaultProject() : void
    {
        $model = new Project();
        $this->assertTrue($model->validate(['is_default']));

        $model = new Project();
        $model->setAttributes([
            'user_id' => 1,
            'is_default' => 1,
        ]);
        $this->assertFalse($model->validate(['is_default']));
        $this->assertEquals('User can have only one default project', $model->getFirstError('is_default'));

        $model = new Project();
        $model->setAttributes([
            'user_id' => 1,
            'is_default' => 0,
        ]);
        $this->assertTrue($model->validate(['is_default']));

        $model = Project::findOne([
            'user_id' => 1,
            'is_default' => 1,
        ]);
        $model->setAttributes([
            'is_default' => 0,
        ]);
        $this->assertFalse($model->validate(['is_default']));
        $this->assertEquals('Is not available to change attribute "is_default"', $model->getFirstError('is_default'));

        $model = Project::findOne([
            'user_id' => 1,
            'is_default' => 1,
        ]);
        $model->setAttributes([
            'title' => 'new title',
        ]);
        $this->assertTrue($model->validate(['is_default']));

        $model = Project::findOne([
            'user_id' => 1,
            'is_default' => 0,
        ]);
        $model->setAttributes([
            'title' => 'new title',
        ]);
        $this->assertTrue($model->validate(['is_default']));
    }
}

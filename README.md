<p align="center">
    <h1 align="center">Task manager api</h1>
    <br>
</p>
<p>
<img src="https://travis-ci.org/lenivezzz/todo-task-api.svg?branch=master" />
</p>
<p>
API for manage task list.
</p>


```
composer install
php yii init
php yii_test migrate
php -S 127.0.0.1:8080 -t api/web
./vendor/bin/codecept build
./vendor/bin/codecept run -- -c api
```

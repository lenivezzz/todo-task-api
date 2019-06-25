<?php

use yii\bootstrap\Alert;

Alert::begin([
    'options' => [
        'class' => 'alert-success text-center',
    ],
]);

echo 'User confirmation completed. Enjoy our service ;)';
Alert::end();

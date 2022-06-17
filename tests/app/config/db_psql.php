<?php

use yii\db\Connection;

return [
    'class' => Connection::class,
    'dsn' => 'pgsql:host=localhost;dbname=test',
    'username' => 'test',
    'password' => 'test',
    'charset' => 'utf8',
    'enableSchemaCache' => YII_DEBUG ? false : true,
];
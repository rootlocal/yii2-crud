<?php

return [
    'class' => \yii\db\Connection::class,
    'dsn' => 'pgsql:host=localhost;dbname=test',
    'username' => 'test',
    'password' => 'test',
    'charset' => 'utf8',
    'enableSchemaCache' => YII_DEBUG ? false : true,
];
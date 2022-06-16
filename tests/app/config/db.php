<?php

use yii\db\Connection;

return [
    'class' => Connection::class,
    'dsn' => 'sqlite:@runtime/data.db',
];
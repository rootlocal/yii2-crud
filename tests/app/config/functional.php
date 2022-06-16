<?php

/**
 * Application configuration for functional tests
 */

use rootlocal\crud\test\app\models\db\User;

return yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/config.php'),
    require(__DIR__ . '/web.php'),
    [
        'components' => [

            'user' => [
                'identityClass' => User::class,
                'enableAutoLogin' => true,
                'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            ],

            'request' => [
                // it's not recommended to run functional tests with CSRF validation enabled
                'enableCsrfValidation' => false,
                // but if you absolutely need it set cookie domain to localhost
                /*
                'csrfCookie' => [
                    'domain' => 'localhost',
                ],
                */
            ],
        ],
    ]
);

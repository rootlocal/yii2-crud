<?php
/**
 * Application configuration shared by all test types
 */
return [
    'id' => 'test',
    'basePath' => dirname(__DIR__),
    'vendorPath' => dirname(dirname(dirname(__DIR__))) . '/vendor',
    'controllerNamespace' => 'rootlocal\crud\test\app\controllers',
    'language' => 'en-US',
    'sourceLanguage' => 'en-US',
    'charset' => 'utf-8',
    'timeZone' => 'Europe/Moscow',

    'bootstrap' => [
        \rootlocal\crud\Bootstrap::class,
    ],

    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@rootlocal/crud/test/app' => dirname(__DIR__),
    ],

    'modules' => [
    ],

    'controllerMap' => [
        'fixture' => [
            'class' => \yii\console\controllers\FixtureController::class,
            'namespace' => '\tests\codeception\fixtures',
        ],
    ],

    'components' => [

        'assetManager' => [
            'basePath' => dirname(__DIR__) . '/web/assets',
            'linkAssets' => true,
            'appendTimestamp' => true,
        ],

        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'sqlite:@runtime/data.db',
        ],

        'mailer' => [
            'useFileTransport' => true,
        ],

        'urlManager' => [
            'showScriptName' => true,
            'enablePrettyUrl' => true,
        ],

        'cache' => [
            'class' => \yii\caching\DummyCache::class,
        ],
    ],
];

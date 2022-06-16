<?php
/**
 * Application configuration shared by all test types
 */

use rootlocal\crud\Bootstrap;
use yii\caching\DummyCache;
use yii\console\controllers\FixtureController;
use yii\swiftmailer\Mailer;

return [
    'id' => 'test',
    'basePath' => dirname(__DIR__),
    'vendorPath' => dirname(__DIR__, 3) . '/vendor',
    'controllerNamespace' => 'rootlocal\crud\test\app\controllers',
    'language' => 'en-US',
    'sourceLanguage' => 'en-US',
    'charset' => 'utf-8',
    'timeZone' => 'Europe/Moscow',

    'bootstrap' => [
        Bootstrap::class,
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
            'class' => FixtureController::class,
            'namespace' => '\tests\codeception\fixtures',
        ],
    ],

    'components' => [

        'assetManager' => [
            'basePath' => dirname(__DIR__) . '/web/assets',
            'linkAssets' => true,
            'appendTimestamp' => true,
        ],

        'db' => require(__DIR__ . '/db.php'),

        'mailer' => [
            'class' => Mailer::class,
            'viewPath' => '@app/mail',
            'useFileTransport' => true,
        ],

        'urlManager' => [
            'showScriptName' => true,
            'enablePrettyUrl' => true,
        ],

        'cache' => [
            'class' => DummyCache::class,
        ],
    ],
];

<?php

namespace rootlocal\crud;

use yii\base\BootstrapInterface;
use yii\base\Application;

/**
 * Class Bootstrap
 * @package rootlocal\crud
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @{inheritdoc}
     * @param Application $app
     */
    public function bootstrap($app)
    {
        // add module I18N category
        if (!isset($app->i18n->translations['rootlocal/crud'])) {
            $app->i18n->translations['rootlocal/crud'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => __DIR__ . '/messages',
            ];
        }
    }
}
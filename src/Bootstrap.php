<?php

namespace rootlocal\crud;

use yii\base\BootstrapInterface;
use yii\base\Application;

/**
 * Class Bootstrap
 * Application bootstrap process
 *
 * @see \yii\base\BootstrapInterface
 *
 * @author Alexander Zakharov <sys@eml.ru>
 * @package rootlocal\crud
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
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
<?php

namespace rootlocal\crud;

use yii\base\BootstrapInterface;

/**
 * Class Bootstrap
 * @package rootlocal\crud
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        // add module I18N category
        if (!isset($app->i18n->translations['rootlocal/crud'])) {
            $app->i18n->translations['rootlocal/crud'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => '@rootlocal/crud/messages',
            ];
        }
    }
}
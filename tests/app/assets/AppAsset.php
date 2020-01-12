<?php

namespace rootlocal\crud\test\app\assets;

use yii\bootstrap\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Class AppAsset Main backend application asset bundle.
 * @package rootlocal\crud\test\app\assets
 */
class AppAsset extends AssetBundle
{
    /**
     * @var array
     */
    public $css = [
        'css/site.css',
    ];

    /**
     * @var array
     */
    public $js = [
    ];

    /**
     * @var array
     */
    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->sourcePath = __DIR__ . '/app';
    }
}

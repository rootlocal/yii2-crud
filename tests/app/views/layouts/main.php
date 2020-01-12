<?php

/**
 * @var $this View
 * @var $content string
 */

use yii\web\View;
use rootlocal\crud\test\app\assets\AppAsset;

AppAsset::register($this);
?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <?= $this->render('head') ?>
    </head>
    <body>

    <?php $this->beginBody() ?>

    <div class="wrap">
        <?= $this->render('menu') ?>
        <div class="container">
            <?= $this->render('top') ?>
            <?= $this->render('content', ['content' => $content]) ?>
        </div>
    </div>

    <?= $this->render('footer') ?>
    <?php $this->endBody() ?>

    </body>
    </html>
<?php $this->endPage() ?>
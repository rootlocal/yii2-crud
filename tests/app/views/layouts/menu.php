<?php

/**
 * @var $this View
 */

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\web\View;

?>

<?php NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse',
    ],
]);

$menuItems = [
    ['label' => 'Home', 'url' => ['/site/index']],
    ['label' => 'About', 'url' => ['/site/about']],
    ['label' => 'Books', 'url' => ['/book/index']],
    ['label' => 'CRUD test controller', 'url' => ['/crud/index']],
];

echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $menuItems,
]);

NavBar::end(); ?>

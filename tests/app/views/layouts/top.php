<?php

/**
 * @var $this View
 */

use yii\helpers\Html;
use yii\web\View;
use rootlocal\crud\test\app\widgets\Alert;
use yii\widgets\Breadcrumbs;
use yii\helpers\Inflector;

?>

<div class="row">
    <div class="col-md-12">
        <?php if (isset($this->blocks['content-header'])): ?>
            <h1><?= $this->blocks['content-header'] ?></h1>
        <?php else: ?>
            <?php if ($this->title !== null): ?>
                <h1><?= Html::encode($this->title) ?></h1>
            <?php else: ?>
                <h1><?= Inflector::camel2words(Inflector::id2camel($this->context->module->id)) ?>
                    <?= ($this->context->module->id !== Yii::$app->id) ? '<small>Module</small>' : '' ?></h1>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?= Alert::widget() ?>
    </div>
</div>


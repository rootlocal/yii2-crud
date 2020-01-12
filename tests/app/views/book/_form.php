<?php

use rootlocal\crud\test\app\models\db\Book;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/**
 * @var $this View
 * @var $model Book
 * @var $form ActiveForm
 */
?>

<div class="book-form">

    <?php $form = ActiveForm::begin([
        'id' => 'book-form',
        'enableAjaxValidation' => true,
        'validationUrl' => Url::to(['validate',
            'id' => $model->isNewRecord ? null : $model->id,
            'scenario' => $model->isNewRecord ? $model::SCENARIO_CREATE : $model::SCENARIO_UPDATE,
        ])]); ?>

    <?= $form->errorSummary($model) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'status')->dropDownList($model->getStatusItems()) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-sm']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

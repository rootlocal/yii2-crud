<?php

use rootlocal\crud\test\app\models\db\Book;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;
use yii\helpers\Url;

/**
 * @var $this View
 * @var $model Book
 */

$this->title = 'View ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="book-view">

    <div class="form-group">
        <?= Html::a('Update', Url::to(['update', 'id' => $model->id]), [
            'class' => 'btn btn-success btn-sm item-update-book',
        ]) ?>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'updated_at:datetime',
            'created_at:datetime',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {

                    /**
                     * @var $model Book
                     */
                    return $model->getStatusItem($model->status);
                }
            ],
        ],
    ]) ?>
</div>
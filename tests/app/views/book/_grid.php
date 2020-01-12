<?php

use rootlocal\crud\test\app\models\search\BookSearch;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\SerialColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;

/**
 * @var $this View
 * @var $searchModel BookSearch
 * @var $dataProvider ActiveDataProvider
 */
?>

<div class="table-responsive">

    <?php Pjax::begin([
        'id' => 'book-grid-pjax',
        'scrollTo' => true,
    ]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{summary}\n{pager}\n{items}\n{pager}",
        'tableOptions' => [
            'class' => 'table table-striped table-bordered table-hover',
        ],
        'columns' => [
            [
                'class' => SerialColumn::class,
                'headerOptions' => ['width' => '40px', 'class' => 'text-center'],
            ],

            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model) {

                    /**
                     * @var $model BookSearch
                     */
                    return Html::a($model->name, $model->getUrl(), [
                            'class' => 'item-view-book',
                            'data-pjax' => 0,
                        ]);
                }
            ],

            [
                'attribute' => 'created_at',
                'headerOptions' => ['width' => '150px', 'class' => 'text-center'],
                'format' => 'datetime',
                'filter' => false,
            ],
            [
                'attribute' => 'updated_at',
                'headerOptions' => ['width' => '150px', 'class' => 'text-center'],
                'format' => 'datetime',
                'filter' => false,
            ],
            [
                'attribute' => 'status',
                'headerOptions' => ['width' => '90px', 'class' => 'text-center'],
                'contentOptions' => function ($model) {

                    /**
                     * @var $model BookSearch
                     */
                    return ['class' => $model->status === $model::STATUS_ACTIVE ? 'success' : 'danger'];
                },
                'format' => 'raw',
                'filter' => BookSearch::instance()->getStatusItems(),
                'value' => function ($model) {

                    /**
                     * @var $model BookSearch
                     */
                    return $model->getStatusItem($model->status);
                }
            ],

            [
                'class' => ActionColumn::class,
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>

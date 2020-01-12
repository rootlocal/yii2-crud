<?php

/**
 * @var $this View
 * @var $searchModel BookSearch
 * @var $dataProvider ActiveDataProvider
 */

use rootlocal\crud\test\app\models\search\BookSearch;
use yii\web\View;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Index Books';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="book-index">
    <div class="form-group">
        <?= Html::a('Create Book', Url::to(['create']), [
            'class' => 'btn btn-success btn-sm',
            'id' => 'create-book',
        ]) ?>
    </div>

    <?= $this->render('_grid', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>
</div>
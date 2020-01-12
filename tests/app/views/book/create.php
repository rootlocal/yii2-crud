<?php

use rootlocal\crud\test\app\models\db\Book;
use yii\web\View;

/**
 * @var $this View
 * @var $model Book
 */

$this->title = 'Create Book';
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="book-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>

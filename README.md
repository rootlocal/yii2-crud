Create, read, update and delete Actions
=========================================

[![Latest Stable Version](https://img.shields.io/packagist/v/rootlocal/yii2-crud.svg)](https://packagist.org/packages/rootlocal/yii2-crud)
[![Build Status](https://travis-ci.org/rootlocal/yii2-crud.svg?branch=master)](https://travis-ci.org/rootlocal/yii2-crud)
[![Test Coverage](https://api.codeclimate.com/v1/badges/cb5826d209c0ec9ac0d6/test_coverage)](https://codeclimate.com/github/rootlocal/yii2-crud/test_coverage)

* [API Documentation](http://rootlocal.github.io/yii2/rootlocal-crud-controllers-activecontroller.html)
* [Source code](https://github.com/rootlocal/yii2-crud)

## Install
```
composer require rootlocal/yii2-crud
```
or add

```json
"rootlocal/yii2-crud": "~1.0",
```

to the require section of your composer.json.

#### Standard Controller:
```php

<?php

namespace rootlocal\crud\test\app\controllers;

use rootlocal\crud\test\app\models\db\Book;
use rootlocal\crud\test\app\models\search\BookSearch;
use rootlocal\crud\controllers\ActiveController;

/**
 * Class CrudController
 * @package rootlocal\crud\test\app\controllers
 */
class CrudController extends ActiveController
{
    /** @var string */
    public $modelClass = Book::class;
    /** @var string */
    public $modelSearchClass = BookSearch::class;
}
```

OR

```php
<?php

use rootlocal\crud\test\app\models\db\Book;
use rootlocal\crud\test\app\models\search\BookSearch;
use yii\web\Controller;
use rootlocal\crud\actions\CreateAction;
use rootlocal\crud\actions\DeleteAction;
use rootlocal\crud\actions\IndexAction;
use rootlocal\crud\actions\UpdateAction;
use rootlocal\crud\actions\ValidateAction;
use rootlocal\crud\actions\ViewAction;

/**
 * Class BookController
 * @package rootlocal\crud\test\app\controllers
 */
class BookController extends Controller
{
    // ...

    /**
     * {@inheritdoc}
     * @return array
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'searchModel' => BookSearch::class,
            ],

            'view' => [
                'class' => ViewAction::class,
                'model' => Book::class,
            ],

            'validate' => [
                'class' => ValidateAction::class,
                'model' => Book::class,
                'scenario' => Book::SCENARIO_CREATE,
            ],

            'create' => [
                'class' => CreateAction::class,
                'model' => Book::class,
                'scenario' => Book::SCENARIO_CREATE,
            ],

            'update' => [
                'class' => UpdateAction::class,
                'model' => Book::class,
                'scenario' => Book::SCENARIO_UPDATE,
            ],

            'delete' => [
                'class' => DeleteAction::class,
                'model' => Book::class,
            ],
        ];
    }
    
    // ...
}
```

#### Form:
```php
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
```

#### Search Model:
```php
<?php

use rootlocal\crud\test\app\models\db\Book;
use yii\data\ActiveDataProvider;
use rootlocal\crud\components\SearchModelInterface;
use rootlocal\crud\test\app\models\query\BookQuery;

/**
 * Class BookSearch
 * @package rootlocal\crud\test\app\models
 * 
 * @property BookQuery $query
 */
class BookSearch extends Book implements SearchModelInterface
{
    
    /**
     * @var BookQuery
     */
    private $_query;
    
    // ...
    
    /**
      * @return BookQuery
      */
     public function getQuery(): BookQuery
     {
         if ($this->_query === null) {
             $this->_query = self::find();
         }
 
         return $this->_query;
     }
     
     /**
      * @param BookQuery $query
      */
     public function setQuery(BookQuery $query): void
     {
         $this->_query = $query;
     }
     
     /**
      * Creates data provider instance with search query applied
      * @param array $params
      * @return ActiveDataProvider
      */
     public function search($params = [])
     {
         
         $query = $this->getQuery();
         $dataProvider = new ActiveDataProvider([
                        'query' => $query,
                    ]);
         // ...
            
         return $dataProvider;
     }
}
```

#### Extended Controller:
```php
<?php

use rootlocal\crud\test\app\models\db\Book;
use rootlocal\crud\test\app\models\search\BookSearch;
use yii\web\Controller;
use rootlocal\crud\actions\CreateAction;
use rootlocal\crud\actions\DeleteAction;
use rootlocal\crud\actions\IndexAction;
use rootlocal\crud\actions\UpdateAction;
use rootlocal\crud\actions\ValidateAction;
use rootlocal\crud\actions\ViewAction;

/**
 * Class BookController
 * @package rootlocal\crud\test\app\controllers
 */
class BookController extends Controller
{
    // ...

    /**
     * {@inheritdoc}
     * @return array
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'searchModel' => BookSearch::class,
                'dataProvider' => function ($model, $queryParams) {
                    /**
                     * @var BookSearch $model
                     * @var array $queryParams
                     */
                    $model->query = $model::find()->active();
                    return $model->search($queryParams);
                }                
            ],

            'view' => [
                'class' => ViewAction::class,
                'model' => function ($id) {
                    /**
                     * @var int $id
                     */
                    return Book::find()->active()->where(['id' => $id])->one();
                }                
            ],

            // etc ...
        ];
    }
    
    // ...
}
```


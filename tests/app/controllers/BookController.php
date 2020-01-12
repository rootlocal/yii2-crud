<?php

namespace rootlocal\crud\test\app\controllers;

use rootlocal\crud\test\app\models\db\Book;
use rootlocal\crud\test\app\models\search\BookSearch;
use yii\filters\VerbFilter;
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

    /**
     * {@inheritdoc}
     * @return array
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['GET'],
                    'view' => ['GET'],
                    'view-lambda' => ['GET'],
                    'create' => ['GET', 'POST'],
                    'update' => ['GET', 'PUT', 'POST'],
                    'update-lambda' => ['GET', 'PUT', 'POST'],
                    'delete' => ['POST', 'DELETE'],
                    'delete-lambda' => ['POST', 'DELETE'],
                    'validate' => ['POST'],
                    'validate-lambda' => ['POST'],
                    'validate-activerecord' => ['POST'],
                ],
            ],
        ];
    }

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

            'view-lambda' => [
                'class' => ViewAction::class,
                'model' => function ($id) {

                    /**
                     * @var int $id
                     */
                    return Book::find()->active()->where(['id' => $id])->one();
                }
            ],

            'validate' => [
                'class' => ValidateAction::class,
                'model' => Book::class,
                'scenario' => Book::SCENARIO_CREATE,
            ],

            'validate-lambda' => [
                'class' => ValidateAction::class,
                'scenario' => Book::SCENARIO_CREATE,
                'model' => function ($id, $scenario) {
                    /**
                     * @var int $id
                     * @var string $scenario
                     */

                    if ($id === null) {
                        return new Book(['scenario' => $scenario]);
                    }

                    $book = Book::find()->active()->where(['id' => $id])->one();

                    if ($book !== null) {
                        $book->scenario = $scenario;
                    }

                    return $book;
                },
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

            'update-lambda' => [
                'class' => UpdateAction::class,
                'scenario' => Book::SCENARIO_UPDATE,
                'model' => function ($id) {

                    /**
                     * @var int $id
                     */
                    return Book::find()->active()->where(['id' => $id])->one();
                }
            ],

            'delete' => [
                'class' => DeleteAction::class,
                'model' => Book::class,
            ],

            'delete-lambda' => [
                'class' => DeleteAction::class,
                'model' => function ($id) {

                    /**
                     * @var int $id
                     */
                    return Book::find()->active()->where(['id' => $id])->one();
                }
            ],
        ];
    }
}
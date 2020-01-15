<?php

namespace rootlocal\crud\controllers;

use rootlocal\crud\components\SearchModelInterface;
use rootlocal\crud\components\Controller;
use rootlocal\crud\actions\CreateAction;
use rootlocal\crud\actions\DeleteAction;
use rootlocal\crud\actions\IndexAction;
use rootlocal\crud\actions\UpdateAction;
use rootlocal\crud\actions\ValidateAction;
use rootlocal\crud\actions\ViewAction;
use yii\data\ActiveDataProvider;

/**
 * Base CRUD Controller
 *
 * The class of the ActiveRecord should be specified via [[modelClass]], [[modelSearchClass]]
 * which must implement [[\yii\db\ActiveRecordInterface]]. By default, the following actions are supported:
 *
 * - `index`: list of models
 * - `view`: return the details of a model
 * - `create`: create a new model
 * - `update`: update an existing model
 * - `delete`: delete an existing model
 * - `validate`: validate model
 *
 * Example:
 *
 * ```php
 * use rootlocal\crud\test\app\models\db\Book;
 * use rootlocal\crud\test\app\models\search\BookSearch;
 *
 * class CrudController extends ActiveController
 * {
 *      public $modelClass = Book::class;
 *      public $modelSearchClass = BookSearch::class;
 * }
 * ```
 *
 * You may disable some of these actions by overriding [[actions()]] and unsetting the corresponding actions.
 *
 * To add a new action, either override [[actions()]] by appending a new action class or write a new action method.
 * Make sure you also override [[verbs()]] to properly declare what HTTP methods are allowed by the new action.
 *
 * You should usually override [[checkAccess()]] to check whether the current user has the privilege to perform
 * the specified action against the specified model.
 *
 * @property string $modelClass string model class name
 * @property string $modelSearchClass string search model name
 *
 * @author Alexander Zakharov <sys@eml.ru>
 * @since 1.0.6
 * @package rootlocal\crud\controllers
 */
class ActiveController extends Controller
{
    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function verbs()
    {
        return [
            'index' => ['GET'],
            'view' => ['GET'],
            'create' => ['GET', 'POST'],
            'update' => ['GET', 'PUT', 'POST'],
            'delete' => ['POST', 'DELETE'],
            'validate' => ['POST'],
        ];
    }

    /**
     * Declares external actions for the controller
     *
     * {@inheritdoc}
     *
     * Examples:
     *
     * ```php
     * public function actions()
     * {
     *      $actions = parent::actions();
     *      unset($actions['delete'], $actions['create']);
     *      $actions['index']['dataProvider'] = [$this, 'getDataProvider'];
     *      return $actions;
     * }
     *
     * public function getDataProvider()
     * {
     * }
     * ```
     *
     * @return array
     */
    public function actions()
    {
        parent::actions();
        return [
            'index' => [
                'class' => IndexAction::class,
                'searchModel' => $this->modelSearchClass,
                'checkAccess' => [$this, 'checkAccess'],
                'dataProvider' => [$this, 'getDataProvider'],
            ],

            'view' => [
                'class' => ViewAction::class,
                'model' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],

            'validate' => [
                'class' => ValidateAction::class,
                'model' => $this->modelClass,
                'scenario' => $this->createScenario,
                'checkAccess' => [$this, 'checkAccess'],
            ],

            'create' => [
                'class' => CreateAction::class,
                'model' => $this->modelClass,
                'scenario' => $this->createScenario,
                'checkAccess' => [$this, 'checkAccess'],
            ],

            'update' => [
                'class' => UpdateAction::class,
                'model' => $this->modelClass,
                'scenario' => $this->updateScenario,
                'checkAccess' => [$this, 'checkAccess'],
            ],

            'delete' => [
                'class' => DeleteAction::class,
                'model' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param SearchModelInterface $model ActiveRecord searchModel
     * @param array $queryParams The request GET parameter values.
     * @return ActiveDataProvider ActiveDataProvider object
     *
     * @see SearchModelInterface::search()
     * @see ActiveDataProvider
     */
    public function getDataProvider($model, $queryParams = []): ActiveDataProvider
    {
        return $model->search($queryParams);
    }

    /**
     * Checks the privilege of the current user.
     *
     * This method should be overridden to check whether the current user has the privilege
     * to run the specified action against the specified data model.
     * If the user does not have access, a [[\yii\web\ForbiddenHttpException]] should be thrown.
     *
     * Example:
     *
     * ```php
     * public function checkAccess($action, $model = null)
     * {
     *      if ($action === 'update' || $action === 'delete') {
     *          if ($model->author_id !== \Yii::$app->user->id){
     *              throw new \yii\web\ForbiddenHttpException(sprintf(
     *                  'You can only %s articles that you\'ve created.', $action));
     *          }
     *      }
     * }
     * ```
     *
     * @param string $action the ID of the action to be executed
     * @param object|null $model the model to be accessed. If null, it means no specific model is being accessed.
     */
    public function checkAccess($action, $model = null)
    {
    }

    /**
     * {@inheritdoc}
     * @return array
     */
    public function accessControl()
    {
        return parent::accessControl();
    }

}
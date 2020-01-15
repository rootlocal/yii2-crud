<?php

namespace rootlocal\crud\test\app\controllers;

use rootlocal\crud\test\app\models\db\Book;
use rootlocal\crud\test\app\models\search\BookSearch;
use rootlocal\crud\controllers\ActiveController;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;

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


    /**
     * {@inheritdoc}
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        return $behaviors;
    }

    /**
     * {@inheritdoc}
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();

        return $actions;
    }

    /**
     * @param BookSearch $model
     * @param array $queryParams
     * @return ActiveDataProvider
     */
    public function getDataProvider($model, $queryParams = []): ActiveDataProvider
    {
        $model->query = $model::find()->active();

        return $model->search($queryParams);
    }

    /**
     * {@inheritdoc}
     * @param string $action
     * @param Book|BookSearch $model
     * @param array $params
     * @throws ForbiddenHttpException
     */
    public function checkAccess($action, $model = null)
    {
        if ($action === 'update' || $action === 'delete') {

            if (isset($model->name) && $model->name === 'checkAccessForbidden') {
                throw new ForbiddenHttpException(
                    sprintf('You can only %s articles that you\'ve created.', $action)
                );
            }
        }
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getViewPath()
    {
        return $this->module->getViewPath() . DIRECTORY_SEPARATOR . 'book';
    }
}
<?php

namespace rootlocal\crud\actions;

use Yii;
use yii\web\Response;
use yii\base\ErrorException;
use yii\data\ActiveDataProvider;
use yii\base\InvalidConfigException;
use Closure;
use rootlocal\crud\components\Action;
use rootlocal\crud\components\SearchModelInterface;

/**
 * Class IndexAction
 * Lists all SearchModelInterface models.
 *
 * examples:
 *
 * ```php
 * // lambda function:
 * public function actions()
 * {
 *      'index' => [
 *          'class' => IndexAction::class,
 *          'searchModel' => function () {
 *              return new BookSearch();
 *          },
 *          'dataProvider' => function ($model, $queryParams) {
 *              $model->query = $model::find()->active();
 *              return $model->search($queryParams);
 *          }
 *      ]
 * }
 *
 * // string:
 * public function actions()
 * {
 *      'index' => [
 *          'class' => IndexAction::class,
 *          'searchModel' => BookSearch::class
 *      ]
 * }
 *
 * // ActiveRecord:
 * public function actions()
 * {
 *      'index' => [
 *          'class' => IndexAction::class,
 *          'model' => new BookSearch()
 *      ]
 * }
 * ```
 * @property Closure|null $dataProvider implements a data provider (instanceof [[Closure]])
 * @property string|SearchModelInterface|Closure $searchModel ActiveRecord searchModel
 * @property SearchModelInterface $model Readonly ActiveRecord object instanceof [[SearchModelInterface]]
 * @property array $queryParams The request GET parameter values.
 *
 * @author Alexander Zakharov <sys@eml.ru>
 * @package rootlocal\crud\actions
 */
class IndexAction extends Action
{
    /**
     * @var string|SearchModelInterface|Closure
     */
    private $_searchModel;
    /**
     * @var SearchModelInterface
     */
    private $_model;
    /**
     * @var ActiveDataProvider
     */
    private $_dataProvider;
    /**
     * @var array $queryParams The request GET parameter values.
     */
    private $_queryParams;


    /**
     * Runs the action.
     *
     * @return string
     */
    public function run()
    {
        $request = Yii::$app->getRequest();

        if ($request->isAjax || $request->isPjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $this->controller->renderAjax($this->getViewName(), [
                'searchModel' => $this->getModel(),
                'dataProvider' => $this->getDataProvider(),
            ]);
        }

        return $this->controller->render($this->getViewName(), [
            'searchModel' => $this->getModel(),
            'dataProvider' => $this->getDataProvider(),
        ]);
    }

    /**
     * Get ActiveRecord searchModel
     *
     * @return Closure|string|SearchModelInterface|array ActiveRecord searchModel
     * @throws ErrorException if Model not specified (not set)
     */
    public function getSearchModel()
    {
        if (empty($this->_searchModel)) {
            throw new ErrorException(Yii::t('rootlocal/crud', 'No search model specified'));
        }

        return $this->_searchModel;
    }

    /**
     * Set ActiveRecord searchModel
     *
     * @param Closure|string|SearchModelInterface|array $searchModel ActiveRecord searchModel
     */
    public function setSearchModel($searchModel): void
    {
        $this->_searchModel = $searchModel;
    }

    /**
     * Get ActiveRecord searchModel
     *
     * @return SearchModelInterface ActiveRecord searchModel object instanceof [[SearchModelInterface]]
     * @throws ErrorException if searchModel not instanceof [[SearchModelInterface]]
     * @throws InvalidConfigException if the configuration is invalid
     */
    public function getModel(): SearchModelInterface
    {
        if ($this->_model === null) {

            if (is_string($this->getSearchModel())) {
                $this->_model = Yii::createObject(['class' => $this->getSearchModel()]);
            }

            if ($this->getSearchModel() instanceof Closure || is_array($this->getSearchModel())) {
                $this->_model = call_user_func($this->getSearchModel());
            }

            if ($this->getSearchModel() instanceof SearchModelInterface) {
                $this->_model = $this->getSearchModel();
            }
        }

        if ($this->_model instanceof SearchModelInterface) {

            if ($this->checkAccess && ($this->checkAccess instanceof Closure || is_array($this->checkAccess))) {
                call_user_func($this->checkAccess, $this->id, $this->_model);
            }

            return $this->_model;
        }

        throw new ErrorException('searchModel not instanceof ' . SearchModelInterface::class);
    }

    /**
     * Get The request GET parameter values.
     *
     * @return array The request GET parameter values.
     */
    public function getQueryParams()
    {
        if ($this->_queryParams === null) {

            $queryParams = Yii::$app->getRequest()->getQueryParams();

            if (empty($queryParams)) {
                $queryParams = Yii::$app->getRequest()->getBodyParams();
            }

            $this->_queryParams = $queryParams;
        }

        return $this->_queryParams;
    }

    /**
     * Get DataProvider object
     *
     * @return ActiveDataProvider ActiveDataProvider object
     * @throws InvalidConfigException if dataProvider not instanceof [[ActiveDataProvider]]
     */
    public function getDataProvider(): ActiveDataProvider
    {
        if ($this->_dataProvider === null) {
            $this->_dataProvider = $this->getModel()->search($this->getQueryParams());
        }

        if (!($this->_dataProvider instanceof ActiveDataProvider))
            throw new InvalidConfigException('dataProvider not instanceof ' . ActiveDataProvider::class);

        return $this->_dataProvider;
    }

    /**
     * Get DataProvider
     *
     * @param Closure $dataProvider anonymous function for [[ActiveDataProvider]]
     */
    public function setDataProvider($dataProvider): void
    {
        if ($dataProvider instanceof Closure || is_array($dataProvider)) {
            $this->_dataProvider = call_user_func($dataProvider, $this->getModel(), $this->getQueryParams());
        }
    }
}
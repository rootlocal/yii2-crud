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
 * @package rootlocal\crud\actions
 *
 *
 * ```php
 * 'dataProvider' => function ($model, $queryParams) {
 *      return $model->search($queryParams);
 * }
 * ```
 * @property Closure|null $dataProvider an anonymous function
 *
 * ```php
 * 'searchModel' => function () {
 *      return new UserSearch();
 * }
 * ```
 * @property string|SearchModelInterface|Closure $searchModel
 *
 * @property array $queryParams
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
     * @var string
     * the view name.
     */
    public $view = 'index';

    /**
     * @var array $queryParams The request GET parameter values.
     */
    private $_queryParams;

    /**
     * @return string
     */
    public function run()
    {
        $request = Yii::$app->request;

        if ($request->isAjax || $request->isPjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $this->controller->renderAjax($this->view, [
                'searchModel' => $this->getModel(),
                'dataProvider' => $this->getDataProvider(),
            ]);
        }

        return $this->controller->render($this->view, [
            'searchModel' => $this->getModel(),
            'dataProvider' => $this->getDataProvider(),
        ]);
    }

    /**
     * @return Closure|string|SearchModelInterface
     * @throws ErrorException
     */
    public function getSearchModel()
    {
        if ($this->_searchModel === null) {
            throw new ErrorException(Yii::t('rootlocal/crud', 'No search model specified'));
        }

        return $this->_searchModel;
    }

    /**
     * @param $searchModel Closure|string|SearchModelInterface
     */
    public function setSearchModel($searchModel): void
    {
        $this->_searchModel = $searchModel;
    }

    /**
     * @return SearchModelInterface
     * @throws ErrorException
     * @throws InvalidConfigException
     */
    public function getModel(): SearchModelInterface
    {
        if ($this->_model === null) {

            if (is_string($this->getSearchModel())) {
                $this->_model = Yii::createObject(['class' => $this->getSearchModel()]);
            }

            if ($this->getSearchModel() instanceof Closure) {
                $this->_model = call_user_func($this->getSearchModel());
            }

            if ($this->getSearchModel() instanceof SearchModelInterface) {
                $this->_model = $this->getSearchModel();
            }
        }

        if ($this->_model instanceof SearchModelInterface) {
            return $this->_model;
        }

        throw new ErrorException('searchModel not instanceof ' . SearchModelInterface::class);
    }

    /**
     * @return array
     */
    public function getQueryParams()
    {
        if ($this->_queryParams === null)
            $this->_queryParams = Yii::$app->request->getQueryParams();

        return $this->_queryParams;
    }


    /**
     * @return ActiveDataProvider
     * @throws InvalidConfigException
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
     * @param $dataProvider Closure
     */
    public function setDataProvider($dataProvider): void
    {
        if ($dataProvider instanceof Closure) {
            $this->_dataProvider = call_user_func($dataProvider, $this->getModel(), $this->getQueryParams());
        }
    }
}
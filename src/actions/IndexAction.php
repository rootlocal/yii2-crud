<?php

namespace rootlocal\crud\actions;

use Yii;
use yii\base\Model;
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
 * @property Closure $dataProvider an anonymous function
 * ```php
 *              'dataProvider' => function ($model, $queryParams) {
 *                    return $model->search($queryParams);
 *               }
 * ```
 * @property SearchModelInterface $model
 * @property array $queryParams
 */
class IndexAction extends Action
{
    /**
     * @var string|Model|Closure
     * ```php
     *      'searchModel' => function () {
     *          return new UserSearch();
     *      }
     * ```
     */
    public $searchModel;

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
     * @throws InvalidConfigException
     * @throws ErrorException
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
     * @return SearchModelInterface
     * @throws InvalidConfigException
     * @throws ErrorException
     */
    private function getModel()
    {
        if ($this->_model === null) {

            if ($this->searchModel === null) {
                throw new ErrorException(Yii::t('rootlocal/crud', 'No search model specified'));
            }

            if (is_string($this->searchModel)) {
                $this->_model = Yii::createObject(['class' => $this->searchModel]);
            }

            if (is_object($this->searchModel)) {

                if ($this->searchModel instanceof Closure) {
                    $this->_model = call_user_func($this->searchModel);
                }

                if ($this->searchModel instanceof Model) {
                    $this->_model = $this->searchModel;
                }
            }
        }

        return $this->_model;
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
     * @throws ErrorException
     */
    public function getDataProvider()
    {
        if ($this->_dataProvider === null) {
            $dataProvider = $this->getModel()->search($this->getQueryParams());

            if ($dataProvider instanceof ActiveDataProvider)
                $this->_dataProvider = $dataProvider;
            else
                throw new InvalidConfigException('Invalid Configuration for attribute dataProvider');
        }

        return $this->_dataProvider;
    }

    /**
     * @param $dataProvider Closure
     * @throws InvalidConfigException
     * @throws ErrorException
     */
    public function setDataProvider($dataProvider)
    {
        if ($dataProvider instanceof Closure) {
            $this->_dataProvider = call_user_func($dataProvider, $this->getModel(), $this->getQueryParams());
        }
    }
}
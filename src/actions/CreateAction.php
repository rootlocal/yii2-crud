<?php

namespace rootlocal\crud\actions;

use Yii;
use rootlocal\crud\components\Action;
use yii\web\Response;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use Closure;

/**
 * Class CreateAction
 * @package rootlocal\crud\actions
 *
 * ActiveRecord is the base class for classes representing relational data in terms of objects
 * ```php
 * 'model' => function ($id) {
 *      return User::find()->where(['id' => $id])->active()->one();
 * }
 * ```
 * string:
 * ```php
 * 'model' => User::class
 * }
 * ```
 * ActiveRecord:
 * ```php
 * 'model' => new User()
 * }
 * ```
 * @property string|ActiveRecord|Closure $model
 *
 * The scenario that this model is in. Defaults to [[SCENARIO_DEFAULT]]
 * @property string|null $scenario
 */
class CreateAction extends Action
{
    /**
     * @var ActiveRecord
     */
    private $_model;

    /**
     * @var string
     * the view name.
     */
    public $view = 'create';

    /**
     * @var string
     * The Default scenario that this model is in. Defaults to [[SCENARIO_DEFAULT]]
     */
    private $_scenario;

    /**
     * @var string|array $redirect the URL to be redirected to. This can be in one of the following formats:
     *
     * - a string representing a URL (e.g. "http://example.com")
     * - a string representing a URL alias (e.g. "@example.com")
     * - an array in the format of `[$route, ...name-value pairs...]` (e.g. `['site/index', 'ref' => 1]`)
     *   [[Url::to()]] will be used to convert the array into a URL.
     */
    public $redirect;

    /**
     * ``` php
     * <?= Html::a('Create', ['create', 'redirect' => 'index']) ?>
     * ```
     * @param $redirect string|null
     * @return array|string|Response
     * @throws ErrorException
     */
    public function run(string $redirect = null)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
        }

        if ($redirect !== null) {
            $this->redirect = $redirect;
        }

        $model = $this->getModel();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            if ($model->save()) {

                Yii::$app->session->setFlash('success',
                    Yii::t('rootlocal/crud', 'Successfully completed'));

                if ($this->redirect === null) {
                    $this->redirect = ['view', 'id' => $model->getPrimaryKey()];
                }

                return Yii::$app->request->isAjax ? ['success' => true]
                    : $this->controller->redirect($this->redirect);
            }

            Yii::$app->session->setFlash('error',
                Yii::t('rootlocal/crud', 'Unable to save Model'));

            return Yii::$app->request->isAjax ? ['success' => false]
                : $this->controller->redirect($this->redirect);
        }


        if (Yii::$app->request->isAjax) {
            return $this->controller->renderAjax($this->view, ['model' => $model]);
        }

        return $this->controller->render($this->view, ['model' => $model]);
    }

    /**
     * @return ActiveRecord
     * @throws ErrorException
     */
    public function getModel(): ActiveRecord
    {
        if ($this->_model === null) {
            throw new ErrorException(Yii::t('rootlocal/crud', 'Model not specified'));
        }

        return $this->_model;
    }

    /**
     * @param $model string|ActiveRecord|Closure
     * @throws InvalidConfigException
     */
    public function setModel($model): void
    {
        if (is_string($model)) {
            $objectConfig['class'] = $model;
            $objectConfig['scenario'] = $this->getScenario();
            $this->_model = Yii::createObject($objectConfig);
        } else {

            if ($model instanceof ActiveRecord) {
                $this->_model = $model;
            }

            if ($model instanceof Closure) {
                $this->_model = call_user_func($model, $this->getScenario());
            }
        }
    }

    /**
     * @param $scenario string|null
     */
    public function setScenario($scenario): void
    {
        $this->_scenario = $scenario;
    }

    /**
     * @return string|null
     */
    public function getScenario()
    {
        if ($this->_scenario === null) {
            $this->_scenario = ActiveRecord::SCENARIO_DEFAULT;
        }

        return $this->_scenario;
    }
}
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
 * Creates a new [[ActiveRecord]] model.
 * If creation is successful, the browser will be [[redirect]] to the 'view' page.
 *
 * examples:
 *
 * ```php
 * // lambda function:
 * public function actions()
 * {
 *      'create' => [
 *          'class' => CreateAction::class,
 *          'scenario' => Book::SCENARIO_CREATE,
 *          'model' => function ($scenario) {
 *              return new Book(['scenario' => $scenario]);
 *          }
 *      ]
 * }
 *
 * // string:
 * public function actions()
 * {
 *      'create' => [
 *          'class' => CreateAction::class,
 *          'scenario' => Book::SCENARIO_CREATE,
 *          'model' => Book::class
 *      ]
 * }
 *
 * // ActiveRecord:
 * public function actions()
 * {
 *      'create' => [
 *          'class' => CreateAction::class,
 *          'model' => new Book(['scenario' => $scenario])
 *      ]
 * }
 * ```
 *
 * @property string|ActiveRecord|Closure $model ActiveRecord Model
 * @property string|null $scenario scenario for model. Defaults to [[ActiveRecord::SCENARIO_DEFAULT]]
 *
 * @author Alexander Zakharov <sys@eml.ru>
 * @package rootlocal\crud\actions
 */
class CreateAction extends Action
{
    /**
     * @var string the view name.
     */
    public $view = 'create';
    /**
     * @var string|array $redirect the URL to be redirected to. This can be in one of the following formats:
     *
     * - a string representing a URL (e.g. "http://example.com")
     * - a string representing a URL alias (e.g. "@example.com")
     * - an array in the format of `[$route, ...name-value pairs...]` (e.g. `['site/index', 'ref' => 1]`)
     *   [[\yii\helpers\Url::to()]] will be used to convert the array into a URL.
     *
     * Any relative URL that starts with a single forward slash "/" will be converted
     * into an absolute one by prepending it with the host info of the current request.
     *
     * ```php
     * <?= \yii\helpers\Html::a('Create', ['create', 'redirect' => 'index'], [
     * 'class' => 'btn btn-primary btn-sm'
     * ]) ?>
     * ```
     */
    public $redirect;

    /**
     * @var string
     */
    private $_scenario;
    /**
     * @var ActiveRecord
     */
    private $_model;


    /**
     * Runs the action.
     *
     * @param string|null $redirect the URL to be redirected to
     * @return array|string|Response response
     */
    public function run($redirect = null)
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
     * Get ActiveRecord model
     *
     * @return ActiveRecord ActiveRecord object model
     * @throws ErrorException if Model not specified (not set)
     */
    public function getModel(): ActiveRecord
    {
        if (empty($this->_model)) {
            throw new ErrorException(Yii::t('rootlocal/crud', 'Model not specified'));
        }

        return $this->_model;
    }

    /**
     * Set ActiveRecord model
     *
     * @param string|ActiveRecord|Closure $model ActiveRecord model
     * @throws InvalidConfigException if the configuration is invalid
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
     * Set scenario
     *
     * @param string|null $scenario scenario for model. Defaults to [[ActiveRecord::SCENARIO_DEFAULT]]
     */
    public function setScenario($scenario): void
    {
        $this->_scenario = $scenario;
    }

    /**
     * Get scenario
     *
     * @return string scenario for model. Defaults to [[ActiveRecord::SCENARIO_DEFAULT]]
     */
    public function getScenario()
    {
        if ($this->_scenario === null) {
            $this->_scenario = ActiveRecord::SCENARIO_DEFAULT;
        }

        return $this->_scenario;
    }
}
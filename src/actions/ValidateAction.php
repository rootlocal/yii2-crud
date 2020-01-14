<?php

namespace rootlocal\crud\actions;

use Yii;
use rootlocal\crud\components\Action;
use yii\db\ActiveRecord;
use yii\web\Response;
use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\base\InvalidConfigException;
use yii\web\BadRequestHttpException;
use yii\bootstrap\ActiveForm;
use Closure;

/**
 * Class UpdateAction
 * Validate [[ActiveRecord]] model
 *
 * examples:
 *
 * ```php
 * // lambda function:
 * public function actions()
 * {
 *      'validate' => [
 *          'class' => ValidateAction::class,
 *          'scenario' => Book::SCENARIO_CREATE,
 *          'model' => function ($id, $scenario) {
 *
 *              if ($id === null) {
 *                  return new Book(['scenario' => $scenario]);
 *              }
 *
 *              $book = Book::find()->active()->where(['id' => $id])->one();
 *              if ($book !== null) {
 *                  $book->scenario = $scenario;
 *              }
 *
 *              return $book;
 *          }
 *      ]
 * }
 *
 * // string:
 * public function actions()
 * {
 *      'validate' => [
 *          'class' => ValidateAction::class,
 *          'scenario' => Book::SCENARIO_CREATE,
 *          'model' => Book::class
 *      ]
 * }
 * ```
 *
 * @property string|Closure $model ActiveRecord model
 * @property string|null $scenario scenario for model. Defaults to [[ActiveRecord::SCENARIO_DEFAULT]]
 *
 * @author Alexander Zakharov <sys@eml.ru>
 * @package rootlocal\crud\actions
 */
class ValidateAction extends Action
{
    /**
     * @var string|Closure
     */
    private $_model;
    /**
     * @var string
     */
    private $_scenario;


    /**
     * Runs the action.
     *
     * @param null|int $id primary key
     * @param null|string $scenario scenario for model. Defaults to [[ActiveRecord::SCENARIO_DEFAULT]]
     * @return array the error message array indexed by the attribute IDs.
     * @throws BadRequestHttpException if Invalid Request
     */
    public function run($id = null, string $scenario = null)
    {
        if ($scenario !== null) {
            $this->scenario = $scenario;
        }

        $model = $this->findModel($id);

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($model);
        }

        throw new BadRequestHttpException(Yii::t('rootlocal/crud', 'Invalid Request'));
    }

    /**
     * Finds the Alias model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string|null $id primary key
     * @return ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException if the configuration is invalid
     */
    protected function findModel($id = null)
    {
        $model = $this->getModel();
        $objectClass = null;

        // is new record
        if ($id === null) {

            if (is_string($model)) {
                $objectConfig['class'] = $model;
                $objectConfig['scenario'] = $this->getScenario();
                $objectClass = Yii::createObject($objectConfig);
            }

            if ($model instanceof Closure) {
                $objectClass = call_user_func($model, $id, $this->getScenario());
            }

            return $objectClass;
        }

        if (is_string($model)) {

            /**
             * @var $model ActiveRecord
             */
            $objectClass = $model::findOne($id);

            if ($objectClass !== null) {
                $objectClass->scenario = $this->getScenario();
            }
        }

        if ($model instanceof Closure) {
            $objectClass = call_user_func($model, $id, $this->getScenario());
        }

        if ($objectClass === null) {
            throw new NotFoundHttpException(Yii::t('rootlocal/crud',
                'The requested page does not exist.'
            )
            );
        }

        return $objectClass;
    }

    /**
     * Get model
     *
     * @return string|Closure|ActiveRecord ActiveRecord model
     * @throws ErrorException if model not specified (not set)
     */
    public function getModel()
    {
        if (empty($this->_model)) {
            throw new ErrorException(Yii::t('rootlocal/crud', 'Model not specified'));
        }

        return $this->_model;
    }

    /**
     * Set model
     *
     * @param string|Closure $model ActiveRecord model
     */
    public function setModel($model): void
    {
        $this->_model = $model;
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
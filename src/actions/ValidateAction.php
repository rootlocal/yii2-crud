<?php

namespace rootlocal\crud\actions;

use Yii;
use rootlocal\crud\components\Action;
use yii\db\ActiveRecord;
use yii\web\Response;
use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\bootstrap\ActiveForm;
use Closure;

/**
 * Class UpdateAction
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
 * @property string|Closure $model
 *
 * The scenario that this model is in. Defaults to [[SCENARIO_DEFAULT]]
 * @property string|null $scenario
 */
class ValidateAction extends Action
{
    /**
     * @var string|Closure
     * ActiveRecord is the base class for classes representing relational data in terms of objects
     */
    private $_model;

    /**
     * @var string
     * The Default scenario that this model is in. Defaults to [[SCENARIO_DEFAULT]]
     */
    private $_scenario;

    /**
     * @param null|int $id
     * @param null|string $scenario
     * @return array the error message array indexed by the attribute IDs.
     * @throws BadRequestHttpException
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
     * @param string|null $id
     * @return ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ErrorException
     */
    protected function findModel($id = null)
    {
        $model = $this->getModel();

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

            /**
             * @var $objectClass ActiveRecord
             */
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

        /**
         * @var $objectClass ActiveRecord
         */
        if ($objectClass === null) {
            throw new NotFoundHttpException(Yii::t('rootlocal/crud',
                'The requested page does not exist.'
            )
            );
        }

        return $objectClass;
    }

    /**
     * @return string|Closure
     * @throws ErrorException
     */
    public function getModel()
    {
        if (empty($this->_model)) {
            throw new ErrorException(Yii::t('rootlocal/crud', 'Model not specified'));
        }

        return $this->_model;
    }

    /**
     * @param $model string|Closure
     */
    public function setModel($model): void
    {
        $this->_model = $model;
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
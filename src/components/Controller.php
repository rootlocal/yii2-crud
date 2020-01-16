<?php

namespace rootlocal\crud\components;

use Yii;
use yii\base\Model;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\base\ErrorException;

/**
 * Class Controller
 *
 * @property string $modelClass string model class name
 * @property string $modelSearchClass string search model name
 *
 * @author Alexander Zakharov <sys@eml.ru>
 * @since 1.0.6
 * @package rootlocal\crud\components
 */
class Controller extends \yii\web\Controller
{
    /**
     * @var string the scenario used for updating a model.
     * @see \yii\base\Model::scenarios()
     */
    public $updateScenario = Model::SCENARIO_DEFAULT;
    /**
     * @var string the scenario used for creating a model.
     * @see \yii\base\Model::scenarios()
     */
    public $createScenario = Model::SCENARIO_DEFAULT;

    /** @var string model class name */
    private $_modelClass;
    /** @var string search model name */
    private $_modelSearchClass;


    /**
     * Get modelClass
     *
     * @return string string model class name
     * @throws ErrorException if Model not specified (not set)
     */
    public function getModelClass()
    {
        if (empty($this->_modelClass)) {
            throw new ErrorException(Yii::t('rootlocal/crud', 'Model not specified'));
        }

        return $this->_modelClass;
    }

    /**
     * Set modelClass
     *
     * @param string $modelClass string model class name
     */
    public function setModelClass($modelClass)
    {
        $this->_modelClass = $modelClass;
    }

    /**
     * Get modelSearchClass
     *
     * @return string string search model name
     * @throws ErrorException if Model not specified (not set)
     */
    public function getModelSearchClass()
    {
        if (empty($this->_modelSearchClass)) {
            throw new ErrorException(Yii::t('rootlocal/crud', 'No search model specified'));
        }

        return $this->_modelSearchClass;
    }

    /**
     * Set modelSearchClass
     *
     * @param string $modelSearchClass string search model name
     */
    public function setModelSearchClass($modelSearchClass)
    {
        $this->_modelSearchClass = $modelSearchClass;
    }

    /**
     * Returns a list of behaviors that this component should behave as.
     *
     * {@inheritdoc}
     *
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        if (!empty($this->verbs())) {
            $behaviors['verbFilter'] = [
                'class' => VerbFilter::class,
                'actions' => $this->verbs(),
            ];
        }

        if (!empty($this->accessControl())) {
            $behaviors['accessFilter'] = [
                'class' => AccessControl::class,
                'rules' => $this->accessControl(),
            ];
        }

        return $behaviors;
    }

    /**
     * Declares the allowed HTTP verbs.
     * Please refer to [[\yii\filters\VerbFilter::actions]] on how to declare the allowed verbs.
     *
     * Example:
     *
     * ```php
     * public function verbs()
     * {
     *      return [
     *          'index' => ['GET'],
     *          'view' => ['GET'],
     *          'create' => ['GET', 'POST'],
     *          'update' => ['GET', 'PUT', 'POST'],
     *          'delete' => ['POST', 'DELETE'],
     *      ];
     * }
     * ```
     *
     * @return array the allowed HTTP verbs.
     */
    protected function verbs()
    {
        return [];
    }

    /**
     * list of access rule objects or configuration arrays for creating the rule objects.
     * If a rule is specified via a configuration array, it will be merged with [[\yii\filters\AccessRule]] first
     * before it is used for creating the rule object.
     *
     * Example:
     *
     * ```php
     * public function accessControl()
     * {
     * return [[
     *      'actions' => [
     *          'index',
     *          'view',
     *          'create',
     *          'update',
     *          'delete',
     *      ],
     *      'allow' => true,
     *      'roles' => ['@']]];
     * }
     * ```
     *
     * @see \yii\filters\AccessRule
     * @return array
     */
    protected function accessControl()
    {
        return [];
    }
}
<?php

namespace rootlocal\crud\components;

use yii\base\Model;
use yii\filters\VerbFilter;

/**
 * Class Controller
 *
 * @author Alexander Zakharov <sys@eml.ru>
 * @since 1.0.6
 * @package rootlocal\crud\components
 */
class Controller extends \yii\web\Controller
{
    /** @var string model class name */
    public $modelClass;
    /** @var string search model name */
    public $modelSearchClass;
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

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbFilter' => [
                'class' => VerbFilter::class,
                'actions' => $this->verbs(),
            ],
        ];
    }

    /**
     * Declares the allowed HTTP verbs.
     * Please refer to [[VerbFilter::actions]] on how to declare the allowed verbs.
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
}
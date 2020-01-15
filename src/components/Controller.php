<?php

namespace rootlocal\crud\components;

use yii\base\Model;

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
}
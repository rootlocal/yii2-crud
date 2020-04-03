<?php

namespace rootlocal\crud\components;

use Closure;

/**
 * Action is the base class for all controller action classes.
 *
 * Action provides a way to reuse action method code. An action method in an Action
 * class can be used in multiple controllers or in different projects.
 *
 * @property string $viewName View Name
 *
 * @see \yii\base\Action
 *
 * @author Alexander Zakharov <sys@eml.ru>
 * @package rootlocal\crud\components
 */
class Action extends \yii\base\Action implements ActionInterface
{

    /**
     * @var Closure|array callable a PHP callable that will be called when running an action to determine
     * if the current user has the permission to execute the action. If not set, the access
     * check will not be performed. The signature of the callable should be as follows,
     *
     * ```php
     * function ($action, $model = null) {
     *     // $model is the requested model instance.
     *     // If null, it means no specific model (e.g. IndexAction)
     * }
     * ```
     * @since 1.0.6
     */
    public $checkAccess;

    /** @var string View Name */
    private $_viewname;

    /**
     * @return string
     */
    public function getViewName()
    {
        if ($this->_viewname === null) {
            $this->_viewname = $this->id;
        }

        return $this->_viewname;
    }

    /**
     * @param string $viewName
     */
    public function setViewName($viewName)
    {
        $this->_viewname = $viewName;
    }
}
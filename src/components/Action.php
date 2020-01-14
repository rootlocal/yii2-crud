<?php

namespace rootlocal\crud\components;

/**
 * Action is the base class for all controller action classes.
 *
 * Action provides a way to reuse action method code. An action method in an Action
 * class can be used in multiple controllers or in different projects.
 *
 * Derived classes must implement a method named `run()`. This method
 * will be invoked by the controller when the action is requested.
 * The `run()` method can have parameters which will be filled up
 * with user input values automatically according to their names.
 * For example, if the `run()` method is declared as follows:
 *
 * ```php
 * public function run($id, $type = 'book') { ... }
 * ```
 *
 * And the parameters provided for the action are: `['id' => 1]`.
 * Then the `run()` method will be invoked as `run(1)` automatically.
 *
 * For more details and usage information on Action, see the
 * [guide article on actions](https://www.yiiframework.com/doc/guide/2.0/en/structure-controllers).
 *
 * @property string $uniqueId The unique ID of this action among the whole application. This property is
 * read-only.
 *
 * @see \yii\base\Action
 *
 * @author Alexander Zakharov <sys@eml.ru>
 * @package rootlocal\crud\components
 */
class Action extends \yii\base\Action implements ActionInterface
{
}
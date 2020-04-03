<?php

namespace rootlocal\crud\actions;

use Yii;
use rootlocal\crud\components\Action;
use yii\db\ActiveRecord;
use yii\web\Response;
use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use Closure;

/**
 * Class ViewAction
 * Displays a single [[ActiveRecord]] model
 *
 * examples:
 *
 * ```php
 * // lambda function:
 * public function actions()
 * {
 *      'view' => [
 *          'class' => ViewAction::class,
 *          'model' => function ($id) {
 *              return Book::find()->where(['id' => $id])->active()->one();
 *          }
 *      ]
 * }
 *
 * // string:
 * public function actions()
 * {
 *      'view' => [
 *          'class' => ViewAction::class,
 *          'model' => Book::class,
 *          'viewName' => 'view'
 *      ]
 * }
 * ```
 *
 * @property string|Closure $model ActiveRecord Model
 *
 * @author Alexander Zakharov <sys@eml.ru>
 * @package rootlocal\crud\actions
 */
class ViewAction extends Action
{
    /**
     * @var string|Closure
     */
    private $_model;


    /**
     * Runs the action.
     *
     * @param int $id primary key
     * @return string response
     */
    public function run($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $this->controller->renderAjax($this->getViewName(), ['model' => $model]);
        }

        return $this->controller->render($this->getViewName(), ['model' => $model]);
    }

    /**
     * Get model
     *
     * @return Closure|string|array ActiveRecord Model
     * @throws ErrorException if Model not specified (not set)
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
     * @param Closure|string|array $model ActiveRecord Model
     */
    public function setModel($model): void
    {
        $this->_model = $model;
    }

    /**
     * Finds the Alias model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id primary key
     * @return ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): ActiveRecord
    {
        $model = $this->getModel();
        $objectClass = null;

        if ($model instanceof Closure || is_array($model)) {
            $objectClass = call_user_func($model, $id);
        } else {
            /** @var ActiveRecord $model $objectClass */
            $objectClass = $model::findOne($id);
        }

        if ($this->checkAccess && ($this->checkAccess instanceof Closure || is_array($this->checkAccess))) {
            call_user_func($this->checkAccess, $this->id, $objectClass);
        }

        if ($objectClass === null) {
            throw new NotFoundHttpException(Yii::t('rootlocal/crud',
                'The requested page does not exist.'));
        }

        return $objectClass;
    }
}
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
 * @package rootlocal\crud\actions
 *
 * ActiveRecord is the base class for classes representing relational data in terms of objects
 * ```php
 * 'model' => function ($id) {
 *      return User::find()->where(['id' => $id])->active()->one();
 * }
 * ```
 * @property string|Closure $model
 */
class ViewAction extends Action
{
    /**
     * @var string|Closure
     */
    private $_model;

    /**
     * @var string
     * the view name.
     */
    public $view = 'view';

    /**
     * @param $id int
     * @return string
     */
    public function run($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $this->controller->renderAjax('view', ['model' => $model]);
        }

        return $this->controller->render('view', ['model' => $model]);
    }

    /**
     * @return Closure|string
     * @throws ErrorException
     */
    public function getModel()
    {
        if ($this->_model === null) {
            throw new ErrorException(Yii::t('rootlocal/crud', 'Model not specified'));
        }

        return $this->_model;
    }

    /**
     * @param $model Closure|string
     */
    public function setModel($model): void
    {
        $this->_model = $model;
    }

    /**
     * Finds the Alias model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ErrorException
     */
    protected function findModel($id): ActiveRecord
    {
        $model = $this->getModel();

        if ($model instanceof Closure) {
            $objectClass = call_user_func($model, $id);
        } else {

            /**
             * @var ActiveRecord $model
             */
            $objectClass = $model::findOne($id);
        }

        if ($objectClass === null) {
            throw new NotFoundHttpException(Yii::t('rootlocal/crud',
                'The requested page does not exist.'
            )
            );
        }

        return $objectClass;
    }
}
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
 */
class ViewAction extends Action
{
    /**
     * @var ActiveRecord|Closure
     * ActiveRecord is the base class for classes representing relational data in terms of objects
     * ```php
     * 'model' => function ($id) {
     *      return User::find()->where(['id' => $id])->active()->one();
     * }
     * ```
     */
    public $model;

    /**
     * @var ActiveRecord
     */
    private $_model;

    /**
     * @var string
     * the view name.
     */
    public $view = 'view';

    /**
     * {@inheritDoc}
     * @throws ErrorException
     */
    public function init()
    {
        parent::init();

        if (empty($this->model)) {
            throw new ErrorException(Yii::t('rootlocal/crud', 'Model not specified'));
        }
    }

    /**
     * @param $id int
     * @return string
     * @throws NotFoundHttpException
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
     * Finds the Alias model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if ($this->_model === null) {

            if ($this->model instanceof Closure) {

                $this->_model = call_user_func($this->model, $id);

            } else {
                $this->_model = $this->model::findOne($id);
            }


            if ($this->_model === null) {
                throw new NotFoundHttpException(Yii::t('rootlocal/crud',
                    'The requested page does not exist.')
                );
            }
        }

        return $this->_model;
    }
}
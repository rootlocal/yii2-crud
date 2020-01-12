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
 * Class UpdateAction
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
class UpdateAction extends Action
{
    /**
     * @var string|Closure
     */
    private $_model;

    /**
     * @var string
     * the view name.
     */
    public $view = 'update';

    /**
     * @var string
     * The scenario that this model is in. Defaults to [[SCENARIO_DEFAULT]]
     */
    public $scenario;

    /**
     * @var string|array $redirect the URL to be redirected to. This can be in one of the following formats:
     *
     * - a string representing a URL (e.g. "http://example.com")
     * - a string representing a URL alias (e.g. "@example.com")
     * - an array in the format of `[$route, ...name-value pairs...]` (e.g. `['site/index', 'ref' => 1]`)
     *   [[Url::to()]] will be used to convert the array into a URL.
     *
     * Any relative URL that starts with a single forward slash "/" will be converted
     * into an absolute one by prepending it with the host info of the current request.
     *
     * <?= Html::a('Update', ['update', 'id' => $model->id, 'redirect' => 'index'], [
     * 'class' => 'btn btn-primary btn-sm'
     * ]) ?>
     */
    public $redirect;


    /**
     * {@inheritDoc}
     * @throws ErrorException
     */
    public function init()
    {
        parent::init();

        if (Yii::$app->request->get('redirect') !== null) {
            $this->redirect = [Yii::$app->request->get('redirect')];
        }
    }

    /**
     * @param $id int
     * @return array|string
     */
    public function run($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($model->validate()) {

                if ($this->redirect === null) {
                    $this->redirect = isset($model->id) ? ['view', 'id' => $model->id] : ['index'];
                }

                if ($model->save()) {
                    Yii::$app->session->setFlash('success',
                        Yii::t('rootlocal/crud', 'Successfully completed'));


                    return Yii::$app->request->isAjax ? ['success' => true]
                        : $this->controller->redirect($this->redirect);
                }

                Yii::$app->session->setFlash('error',
                    Yii::t('rootlocal/crud', 'Unable to save Model'));

                return Yii::$app->request->isAjax ? ['success' => false]
                    : $this->controller->redirect($this->redirect);
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->controller->renderAjax($this->view, ['model' => $model]);
        }

        return $this->controller->render($this->view, ['model' => $model]);
    }

    /**
     * Finds the Alias model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ErrorException
     */
    protected function findModel($id)
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

    /**
     * @return string|Closure
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
     * @param $model
     */
    public function setModel($model): void
    {
        $this->_model = $model;
    }
}
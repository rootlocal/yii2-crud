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
 * Class DeleteAction
 * Deletes an existing [[ActiveRecord]] model.
 * If deletion is successful, the browser will be [[redirect]] to the 'index' page.
 *
 * examples:
 *
 * ```php
 * // lambda function:
 * public function actions()
 * {
 *      'delete' => [
 *          'class' => DeleteAction::class,
 *          'model' => function ($id) {
 *              return User::find()->where(['id' => $id])->active()->one();
 *          }
 *      ]
 * }
 *
 * // string:
 * public function actions()
 * {
 *      'delete' => [
 *          'class' => DeleteAction::class,
 *          'model' => Book::class
 *      ]
 * }
 * ```
 *
 * @property string|Closure $model ActiveRecord Model
 *
 * @author Alexander Zakharov <sys@eml.ru>
 * @package rootlocal\crud\actions
 */
class DeleteAction extends Action
{
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
     * ```php
     * <?= Html::a('Delete', ['delete', 'id' => $model->id, 'redirect' => 'index'], [
     * 'class' => 'btn btn-danger btn-sm',
     * 'data' => ['confirm' => 'Are you sure you want to delete this item?', 'method' => 'POST']
     * ]) ?>
     * ```
     */
    public $redirect;

    /**
     * @var string|Closure ActiveRecord Model
     */
    private $_model;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->redirect === null) {
            $referrer = Yii::$app->request->getReferrer();
            $this->redirect = $referrer === null ? ['index'] : $referrer;
        }
    }

    /**
     * Runs the action.
     *
     * @param int $id primary key
     * @param string|null $redirect the URL to be redirected to
     * @return array|Response response
     */
    public function run($id, $redirect = null)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
        }

        if ($redirect !== null) {
            $this->redirect = $redirect;
        }

        if ($this->findModel($id)->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('rootlocal/crud', 'Entry deleted'));
            return Yii::$app->request->isAjax ? ['success' => true] : $this->controller->redirect($this->redirect);
        }

        Yii::$app->session->setFlash('error', Yii::t('rootlocal/crud', 'Could not delete Entry'));
        return Yii::$app->request->isAjax ? ['success' => false] : $this->controller->redirect($this->redirect);
    }

    /**
     * Finds the Alias model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id primary key
     * @return ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        /** @var ActiveRecord $model */

        $model = $this->getModel();
        $objectClass = null;

        if ($model instanceof Closure) {
            $objectClass = call_user_func($model, $id);
        }

        if (is_string($model)) {

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
     * Get ActiveRecord model
     *
     * @return string|Closure|ActiveRecord ActiveRecord model
     * @throws ErrorException if Model not specified (not set)
     */
    public function getModel()
    {
        if ($this->_model === null) {
            throw new ErrorException(Yii::t('rootlocal/crud', 'Model not specified'));
        }

        return $this->_model;
    }

    /**
     * Set ActiveRecord model
     *
     * @param string|Closure $model ActiveRecord model
     */
    public function setModel($model): void
    {
        $this->_model = $model;
    }
}
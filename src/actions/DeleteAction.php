<?php

namespace rootlocal\crud\actions;

use Throwable;
use Yii;
use rootlocal\crud\components\Action;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\Response;
use yii\base\ErrorException;
use yii\web\NotFoundHttpException;

/**
 * Class ViewAction
 * @package rootlocal\crud\actions
 */
class DeleteAction extends Action
{
    /**
     * @var ActiveRecord
     * ActiveRecord is the base class for classes representing relational data in terms of objects
     */
    public $model;

    /**
     * @var ActiveRecord
     */
    private $_model;

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
     * <?= Html::a('Delete', ['delete', 'id' => $model->id, 'redirect' => 'index'], [
     * 'class' => 'btn btn-danger btn-sm',
     * 'data' => ['confirm' => 'Are you sure you want to delete this item?', 'method' => 'POST']
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

        if (empty($this->model)) {
            throw new ErrorException(Yii::t('rootlocal/crud', 'Model not specified'));
        }

        if (Yii::$app->request->get('redirect') !== null) {
            $this->redirect = [Yii::$app->request->get('redirect')];
        }

        if ($this->redirect === null) {
            $referrer = Yii::$app->request->getReferrer();
            $this->redirect = $referrer === null ? ['index'] : $referrer;
        }

    }

    /**
     * @param $id int
     * @return Response|array
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function run($id)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
        }

        if (!$this->findModel($id)->delete()) {
            Yii::$app->session->setFlash('error',
                Yii::t('rootlocal/crud', 'Could not delete Entry'));

            return Yii::$app->request->isAjax ? ['success' => true] : $this->controller->redirect($this->redirect);
        }

        Yii::$app->session->setFlash('success', Yii::t('rootlocal/crud', 'Entry deleted'));

        return Yii::$app->request->isAjax ? ['success' => false] : $this->controller->redirect($this->redirect);
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
            $this->_model = $this->model::findOne($id);

            if ($this->_model === null) {
                throw new NotFoundHttpException(Yii::t('rootlocal/crud',
                    'The requested page does not exist.')
                );
            }
        }

        return $this->_model;
    }
}
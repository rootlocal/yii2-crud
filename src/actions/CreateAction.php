<?php

namespace rootlocal\crud\actions;

use Yii;
use rootlocal\crud\components\Action;
use yii\base\InvalidConfigException;
use yii\web\Response;
use yii\base\ErrorException;
use yii\db\ActiveRecord;

/**
 * Class CreateAction
 * @package rootlocal\crud\actions
 */
class CreateAction extends Action
{
    /**
     * @var ActiveRecord
     * ActiveRecord is the base class for classes representing relational data in terms of objects
     */
    public $model;

    /**
     * @var string
     * the view name.
     */
    public $view = 'create';

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
     * <?= Html::a('Create', ['create', 'id' => $model->id, 'redirect' => 'index'], [
     * 'class' => 'btn btn-success btn-sm'
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
    }

    /**
     * @return array|string|Response
     * @throws InvalidConfigException
     */
    public function run()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
        }

        $objectConfig['class'] = $this->model;

        if ($this->scenario !== null) {
            $objectConfig['scenario'] = $this->scenario;
        }

        /**
         * @var $model ActiveRecord
         */
        $model = Yii::createObject($objectConfig);

        if ($model->load(Yii::$app->request->post())) {

            if ($model->validate()) {

                if ($model->save()) {

                    Yii::$app->session->setFlash('success',
                        Yii::t('rootlocal/crud', 'Successfully completed'));

                    if ($this->redirect === null) {
                        $this->redirect = isset($model->id) ? ['view', 'id' => $model->id] : ['index'];
                    }

                    return Yii::$app->request->isAjax ? ['success' => true]
                        : $this->controller->redirect($this->redirect);
                }

                Yii::$app->session->setFlash('error',
                    Yii::t('rootlocal/crud', 'Unable to save Model'));

                if ($this->redirect === null) {
                    $this->redirect = ['index'];
                }

                return Yii::$app->request->isAjax ? ['success' => false]
                    : $this->controller->redirect($this->redirect);
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->controller->renderAjax($this->view, ['model' => $model]);
        }

        return $this->controller->render($this->view, ['model' => $model]);
    }

}
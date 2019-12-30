<?php

namespace rootlocal\crud\actions;

use Yii;
use rootlocal\crud\components\Action;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\web\Response;
use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\bootstrap\ActiveForm;

/**
 * Class UpdateAction
 * @package rootlocal\crud\actions
 */
class ValidateAction extends Action
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
     * @var string
     * The Default scenario that this model is in. Defaults to [[SCENARIO_DEFAULT]]
     *
     */
    public $scenario;

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
     * @param null|int $id
     * @param null|string $scenario
     * @return array the error message array indexed by the attribute IDs.
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function run($id = null, $scenario = null)
    {
        if ($scenario !== null) {
            $this->scenario = $scenario;
        }

        $model = $this->findModel($id);

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($model);
        }

        throw new BadRequestHttpException(Yii::t('rootlocal/crud', 'Invalid Request'));
    }

    /**
     * Finds the Alias model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string|null $id
     * @return void|ActiveRecord|null the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    protected function findModel($id = null)
    {
        if ($this->_model === null) {

            if ($id !== null) {
                $this->_model = $this->model::findOne($id);

                if ($this->scenario !== null) {
                    $this->_model->scenario = $this->scenario;
                }

            } else {
                $objectConfig['class'] = $this->model;

                if ($this->scenario !== null) {
                    $objectConfig['scenario'] = $this->scenario;
                }

                $this->_model = Yii::createObject($objectConfig);
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
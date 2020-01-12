<?php

namespace rootlocal\crud\test\app\controllers;

use yii\web\Controller;
use yii\web\ErrorAction;

/**
 * Class SiteController
 * @package rootlocal\crud\test\app\controllers
 */
class SiteController extends Controller
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Displays about page.
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

}

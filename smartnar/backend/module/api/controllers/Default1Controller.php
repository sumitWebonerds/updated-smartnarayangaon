<?php

namespace app\module\api\controllers;

use yii\web\Controller;

/**
 * Default controller for the `api` module
 */
class Default1Controller extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}

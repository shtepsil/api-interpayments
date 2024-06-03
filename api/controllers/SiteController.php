<?php

namespace api\controllers;

/**
 * Site controller
 */
class SiteController extends MainController
{
//    public $modelClass = 'common\models\User';

    public function actionIndex()
    {
        return ['SiteController' => 'actionIndex()'];
    }

    public function actionCheck()
    {
        return 'UserController->check()';
    }

}

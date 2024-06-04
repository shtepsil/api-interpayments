<?php

namespace api\controllers;

use api\components\api\Api;
use common\components\Debugger as d;

/**
 * Site controller
 */
class SiteController extends MainController
{
//    public $modelClass = 'common\models\User';

    public function actionIndex()
    {
        $api = new Api();
        $res = $api->testRequest();
        return $res;
//        return ['SiteController' => 'actionIndex'];
    }

    public function actionCheck()
    {
        return 'UserController->check()';
    }

}

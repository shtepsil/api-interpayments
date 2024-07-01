<?php

namespace api\controllers;

use api\components\api\Api;
use common\components\Debugger as d;
use shadow\helpers\Json;

/**
 * Site controller
 */
class SiteController extends MainController
{
//    public $modelClass = 'common\models\User';

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET'],
            'gross-balance' => ['GET'],
            'gross-payments' => ['GET'],
        ];
    }

    public function actionIndex()
    {
        return ['message' => $this->notFound404];
    }

    /**
     * @return mixed
     */
    public function actionGrossBalance()
    {
        /** @var Api $api */
        $api = $this->api;
        return $api->agentDeposit();
    }

    /**
     * date - тип date, формат дд.мм.гггг
     * @return mixed
     */
    public function actionGrossPayments($date)
    {
        /** @var Api $api */
        $api = $this->api;

        return $api->agentTransactionList([
            'date' => $date,
        ]);
    }

}

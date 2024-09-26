<?php

namespace api\controllers;

use api\components\api\Api;
use api\components\Response;
use backend\components\log\LogUserId;
use common\models\ClientTransactions;
use Yii;
use yii\db\Exception;

class PaymentController extends MainController
{

    private string $agent_transaction_id = '600000001';

    /**
     * @inheritdoc
     */
    protected function verbs(): array
    {
        return [
            'index' => ['POST'],
//            'pay' => ['POST'],
            'check-status' => ['POST'],
            'check-and-pay' => ['POST'],
            // Тестовый action
            'check-and-payy' => ['POST'],
        ];
    }

    public function actionIndex(): array
    {
        return ['message' => $this->notFound404];
    }

    /**
     * Для запроса check:
     * service_id - тип Int, Идентификационный номер Сервиса (Передается со стороны InterHub)
     * account - тип String, Аккаунт или номер телефона
     * amount - тип Double, Сумма, отправленная агентом
     * agent_transaction_id - тип String, Идентификационный номер транзакции на стороне Агента.
     *   Этот id не повторяется, и каждая новая операция должна сопровождаться новым id.
     * params - тип Object, Если необходимо ввести дополнительную информацию помимо account и amount,
     *   то добавляется в этот параметр (не обязателен).
     *
     * Для запроса pay:
     * agent_transaction_id - тип String, Идентификационный номер транзакции на стороне Агента.
     * Этот id не повторяется, и каждая новая операция должна сопровождаться новым id.
     *
     * @return string[]
     * @throws Exception
     */
    public function actionCheckAndPay()
    {
        $post = Yii::$app->request->post();
//        return $post;

        if (!Yii::$app->user->isGuest) {

            $client_id = Yii::$app->user->id;

            if (
                !isset($post['agent_transaction_id'])
                OR !isset($post['account'])
                OR !isset($post['amount'])
            ) {
                return Response::error('Запрос должен содержать обязательные параметры: agent_transaction_id, account, amount.');
            }

            $paramsPaymentCheck = [
                'service_id' => Yii::$app->params['service_id'],
                'account' =>  $post['account'],
                'agent_transaction_id' => $post['agent_transaction_id'],
                'amount' => $post['amount'],
            ];

            $checkResult = $this->api->paymentCheck($paramsPaymentCheck);

            // Если check успешен
            if (is_array($checkResult) AND isset($checkResult['success']) AND $checkResult['success']) {

                LogUserId::info([
                    'type' => 'info',
                    'client_id' => $client_id,
                    'message' => 'Запрос paymentCheck(params) успешен',
                    'params' => $paramsPaymentCheck,
                    'response_message' => $checkResult,
                ], __METHOD__, $client_id);

                $paramsPaymentPay = [
                    'agent_transaction_id' => $post['agent_transaction_id'],
                ];

                $payResult = $this->api->paymentPay($paramsPaymentPay);

                // Если pay успешен
                if ($payResult['success']) {

                    LogUserId::info([
                        'type' => 'info',
                        'client_id' => $client_id,
                        'message' => 'Запрос paymentPay(params) успешен',
                        'params' => $paramsPaymentPay,
                        'response_message' => $payResult,
                    ], __METHOD__, $client_id);

                    // Добавим транзакцию в таблицу транзакций
                    $clientTransaction = new ClientTransactions();

                    $clientTransaction->service_id = Yii::$app->params['service_id'];
                    $clientTransaction->client_id = $client_id;
                    $clientTransaction->account = $post['account'];
                    $clientTransaction->agent_transaction_id = $post['agent_transaction_id'];
                    $clientTransaction->amount = $post['amount'];
                    $clientTransaction->save();

                    // Пока решено сделать без вот этой проверки.
//                    if (!$clientTransaction->save()) {
//
//                        LogUserId::info([
//                            'client_id' => $client_id,
//                            'message' => 'Ошибка запроса paymentPay(...)',
//                            'response_message' => $payResult,
//                            'params' => $paymentPayParams
//                        ], __METHOD__, $client_id);
//
//                        return Response::error('Incorrect data');
//                    }

                    return Response::success($payResult);

                } else {

                    LogUserId::error([
                        'type' => 'error',
                        'client_id' => $client_id,
                        'message' => 'Ошибка запроса paymentPay(params)',
                        'response_message' => $payResult,
                        'params' => $paramsPaymentPay,
                    ], __METHOD__, $client_id);

                    //# если pay вернул ошибку
                    return Response::error($payResult);
                }

            } else {

                LogUserId::error([
                    'type' => 'error',
                    'client_id' => $client_id,
                    'message' => 'Ошибка запроса paymentCheck(params)',
                    'params' => $paramsPaymentCheck,
                    'response_message' => $checkResult,
                ], __METHOD__, $client_id);

                //# если check вернул ошибку
                return Response::error($checkResult);
            }
        } else {
            return Response::error('Unauthorized. Your request was made with invalid credentials.');
        }
    }

    /**
     * @throws Exception
     */
    public function actionCheckAndPayy()
    {

        $log_body1 = [
            'type' => 'info',
            'client_id' => 24,
            'message' => 'Запрос paymentCheck(params) успешен',
            'params' => ['key' => 'value'],
            'response_message' => ['message' => 'response'],
        ];
        $log_body2 = [
            'type' => 'error',
            'client_id' => 24,
            'message' => 'Ошибка запроса paymentCheck(params)',
            'params' => ['key' => 'value'],
            'response_message' => ['message' => 'response'],
        ];

        $time = time();
        LogUserId::info($log_body1, __METHOD__, 24);
        LogUserId::info($log_body1, __METHOD__, 24);
        LogUserId::error($log_body2, __METHOD__, 24);


        return 'Лог записан';

        if (!Yii::$app->user->isGuest) {
            $client_id = Yii::$app->user->id;


//        if (
//            !isset($post['agent_transaction_id'])
//            OR !isset($post['account'])
//            OR !isset($post['amount'])
//        ) {
//            return Response::error('Запрос должен содержать обязательные параметры: agent_transaction_id, account, amount.');
//        }

//            $paramsPaymentCheck = [
//                'service_id' => Yii::$app->params['service_id'],
//                'account' =>  'bowfnadenoc1972',
//                'agent_transaction_id' => $this->agent_transaction_id,
//                'amount' => 0,
//            ];
            $paramsPaymentCheck = [
                'service_id' => Yii::$app->params['service_id'],
                'account' =>  $post['account'],
            ];
            $paramsPaymentCheck['agent_transaction_id'] = $post['agent_transaction_id'];
            $paramsPaymentCheck['amount'] = $post['amount'];
//            $paramsPaymentCheck['agent_transaction_id'] = $get['agent_transaction_id'];
//            $paramsPaymentCheck['amount'] = $get['amount'];

//        d::ajax($params);

            $checkResult = $this->api->paymentCheck($paramsPaymentCheck);
//        return $checkResult;

//        $payResult = $this->api->paymentPay([
//            'agent_transaction_id' => $this->agent_transaction_id
//        ]);
//        return $payResult;

            // Если check успешен
            if (is_array($checkResult) AND isset($checkResult['success']) AND $checkResult['success']) {

                LogUserId::info([
                    'client_id' => $client_id,
                    'message' => 'Запрос paymentCheck(params) успешен',
                    'params' => $paramsPaymentCheck,
                    'response_message' => $checkResult,
                ], __METHOD__, $client_id);

                $paramsPaymentPay = [
                    'agent_transaction_id' => $this->agent_transaction_id
//                'agent_transaction_id' => $post['agent_transaction_id'],
                ];

                $payResult = $this->api->paymentPay($paramsPaymentPay);

                // Если pay успешен
                if ($payResult['success']) {

                    LogUserId::info([
                        'client_id' => $client_id,
                        'message' => 'Запрос paymentPay(params) успешен',
                        'params' => $paramsPaymentPay,
                        'response_message' => $payResult,
                    ], __METHOD__, $client_id);

                    // Добавим транзакцию в таблицу транзакций
                    $clientTransaction = new ClientTransactions();

                    $clientTransaction->service_id = Yii::$app->params['service_id'];
                    $clientTransaction->client_id = $client_id;
                    $clientTransaction->account = $post['account'];
                    $clientTransaction->agent_transaction_id = $post['agent_transaction_id'];
                    $clientTransaction->amount = $post['amount'];
                    $clientTransaction->save();

                    return Response::success($payResult);
                } else {

                    LogUserId::info([
                        'client_id' => $client_id,
                        'message' => 'Ошибка запроса paymentPay(params)',
                        'response_message' => $payResult,
                        'params' => $paramsPaymentPay,
                    ], __METHOD__, $client_id);

                    //# если pay вернул ошибку
                    return Response::error($payResult);
                }

            } else {

                LogUserId::info([
                    'client_id' => $client_id,
                    'message' => 'Ошибка запроса paymentCheck(params)',
                    'params' => $paramsPaymentCheck,
                    'response_message' => $checkResult,
                ], __METHOD__, $client_id);

                //# если check вернул ошибку
                return Response::error($checkResult);
            }

        } else {
            return Response::error('Unauthorized. Your request was made with invalid credentials.');
        }

    }

    /**
     * agent_transaction_id - тип String, Идентификационный номер транзакции на стороне Агента.
     * Этот id не повторяется, и каждая новая операция должна сопровождаться новым id.
     * @return mixed
     */
    public function actionCheckStatus()
    {
        return $this->api->checkStatus([
            'agent_transaction_id' => $this->agent_transaction_id,
        ]);
    }

}//Class


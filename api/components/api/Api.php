<?php
/*
 * Ссылка на документацию Kaspi API
 * https://kaspi.kz/merchantcabinet/support/pages/viewpage.action?pageId=22645486
 */

namespace api\components\api;

use common\components\Debugger as d;

class Api extends ApiBase
{

    public function __construct($token = '')
    {
        //        if($token == ''){
//            throw new CException('Токен обязателен');
//        }
        parent::__construct($token);
    }

    public function test()
    {
        return $this->request('');
    }

    public function users()
    {
//        $params['headers'] = [
//            'Authorization:  Bearer IUn46aR0NFRc0oXdOkmuHhNOwaQd3xW0'
//        ];
//        return $this->request('user/test2', $params);
        return $this->request('user/create', [], 'POST');
    }

    public function testRequest()
    {
        $params = [
            'headers' => [
                'token:  c4ZqQi8pMhJwO1nXbtxrEQ2D5025Nr1zO9umtuDuzmUxPFvQaR'
            ],
            'service_id' => 92,
            'account' =>  'igromen4g',
            'agent_transaction_id' => '54046441',
            'amount' => 1000,
            'params' => []
        ];
        return $this->request('payment/check', $params, 'POST');
    }

    public function actionDebugGet($data = [])
    {
        $user_id = '16893';
        $params = [
            'id' => $user_id,
            'session_id' => $this->token,
            's_get' => 1
        ];

        if (
            isset($_POST['data']) and $_POST['data'] != ''
            and isset($_POST['input_name'])
            and $_POST['input_name'] == 'debug_file_name'
        ) {
            $params['debug_file'] = $_POST['data'];
        }

        return $this->request('siteapi/get-debug', $params);
    }

    public function checkpromo($promo_code = '')
    {
        if ($promo_code == '')
            return false;

        $params = [
            'code' => $promo_code,
            'key' => $this->token,
            'price' => 10000
        ];
        //        d::pe($_SERVER['HTTP_APP_TYPE']);
        if (isset($_SERVER['HTTP_APP_TYPE'])) {
            $params['headers'] = [
                'APP-TYPE: ' . $_SERVER['HTTP_APP_TYPE']
            ];
        }

        return $this->request('basketapi/controlcheckpromo', $params);
    }

} //Class

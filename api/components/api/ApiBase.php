<?php

namespace api\components\api;

use common\components\Debugger as d;
use shadow\helpers\SArrayHelper;

//use apiking\Logger;


class ApiBase
{
    public $token;
    public $client;
    public $params;
    public $config;

    public function __construct($token = '')
    {
        $this->token = $token;
//        $api_url = 'https://testapi.interhub.ae/api';
        $api_url = 'https://api.interhub.ae/api';
//        $api_url = 'https://api-interpayments/api';
        $this->client = new CcUrl([ 'base_url' => $api_url ]);
    }

    protected function request($endpoint = '', $params = [], $method = 'GET')
    {
        if (is_string($params)) {
            $method = $params;
            $params = [];
        }

        $params['base_headers'] = [
            'Content-Type: application/json; charset=UTF-8',
            'Accept: application/json',
            'token: ' . $this->token,
        ];
//        // Базовые параметры для всех запросов
//        $params = SArrayHelper::merge([
//            'service_id' => 92,
//            'account' =>  'igromen4g',
//        ], $params);

        if ($endpoint != '') $endpoint = '/' . $endpoint;
        if ($endpoint == '') $endpoint = $endpoint . '/';
        $response = $this->client->request($endpoint, $params, $method);
//        d::ajax($response);

        if(d::isJson($response['data'])){
            $arr_response = json_decode($response['data'], true);
        }else{
            $arr_response = $response['data'];
        }

        // DEBUG ====================================================
        if (d::$view_response) {
            $arr_response['endpoint'] = $response['endpoint'];
            $arr_response['debug'] = $response['debug'];
        }
        if (d::$view_body) {
            $arr_response = $response['debug']['response']['body'];
        }
        // ==========================================================

        return $arr_response;
    }

}//Class

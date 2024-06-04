<?php

namespace api\components\api;

use common\components\Debugger as d;
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
        $api_url = 'https://testapi.interhub.ae/api';
        $this->client = new CcUrl([ 'base_url' => $api_url ]);
    }

    protected function request($endpoint = '', $params = [], $method = 'GET')
    {
        $arr_response = [];
//        d::ajax($endpoint);
//        d::ajax($params);
        $params['base_headers'] = [
            'Content-Type: application/json; charset=UTF-8',
            'Accept: application/json',
        ];
        if($endpoint != '') $endpoint = '/' . $endpoint;
        if($endpoint == '') $endpoint = $endpoint . '/';
        $response = $this->client->request($endpoint, $params, $method);
//        d::ajax($response);

//        $arr_response = json_decode($response, true);

        if(d::isJson($response['data'])){
            $arr_response = json_decode($response['data'], true);
        }else{
            $arr_response = $response['data'];
        }

        if(!is_array($arr_response)){
            $arr_response = [ 'response' => $arr_response ];
        }

        if(d::$view_response) {
            $arr_response['endpoint'] = $response['endpoint'];
            $arr_response['debug'] = $response['debug'];
        }


        return $arr_response;
    }

}//Class

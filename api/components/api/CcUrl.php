<?php

namespace api\components\api;

use common\components\Debugger as d;
use shadow\helpers\Json;

class CcUrl
{
    public $base_url;

    public function __construct($params = [])
    {
        $this->base_url = $params['base_url'];
    }

    public function request($endpoint = '', $options = [], $method = 'GET'){

        $api_endpoint = $this->base_url.$endpoint;
        $d = [];
        $debug = [
            'request' => [],
            'response' => [],
        ];
        $data = null;

//        d::ajax($api_endpoint);
//        d::pex($api_endpoint);

//        d::ajax($method);

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 1);

//        d::pe($options);
        // Базовые заголовки
        $headers = ($options['base_headers']?:[]);
        // Если есть пользовательские заголовки
        if(isset($options['headers']) AND count($options['headers'])){
            // Объединим базовые заголовки с пользовательскими
            $headers = array_merge( $headers, ($options['headers'])?:[] );
        }
        $d['headers'] = $headers;
        if(d::$get_headers) d::ajax($headers);
        if(count($headers)){
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        $debug['request']['headers'] = $headers;

        unset($options['url_items']);
        unset($options['headers']);
        unset($options['base_headers']);

//        pe($options);
//        d::ajax($options);

        // Если method массив
        if(is_array($method) AND count($method)){
            if($method[0] == 'POST'){
                if(isset($method[1]) AND is_bool($method[1]) AND $method[1] == true){
                    $data = json_encode($options,JSON_UNESCAPED_UNICODE);
                }else{
                    $data = $options;
                }
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }else{
                if(count($options)){
                    $api_endpoint .= '?' . http_build_query($options);
                }
            }
        }else{
        // Если method строка
            if($method == 'POST'){
                $data = $options;
                $data = json_encode($options, 256);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }else{
                if(count($options)){
                    $api_endpoint .= '?' . http_build_query($options);
                }
            }
        }

        if(d::$get_data) d::pe($data);
        if(d::$get_url) d::pe($api_endpoint);

        $d['data'] = (Json::isJson($data)) ? json_decode($data) : $data;
        $d['method'] = $method;
        $debug['request']['body'] = (Json::isJson($data)) ? json_decode($data) : $data;
        $debug['request']['method'] = 'Метод: ' . $method;
        $d['api_endpoint'] = $api_endpoint;
        if(d::$curl) d::ajax($d);

        curl_setopt($curl, CURLOPT_URL, $api_endpoint);
        $response = curl_exec($curl);
        $http_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

//        $debug['response']['data'] = $response;

//        d::pex($response);

        $curlinfo_response = $this->getHeaders($response);
        $debug['response']['headers'] = $curlinfo_response['info'];
        $debug['response']['body'] = Json::isJson($curlinfo_response['body']) ? Json::decode($curlinfo_response['body']) : $curlinfo_response['body'];
        $cookies = $this->getCookie($curlinfo_response['info']);
        $debug['cookie'] = $cookies;
        if(isset($cookies['front_s']) AND isset($_SESSION['codelogin'])){
            $this->setSession($cookies);
            $_SESSION['codelogin'] = 0;
        }

        $response = $curlinfo_response['body'];

        if ($http_status_code == 200){
            $data = $response;
        } else {
            $data = $response;
        }

//        d::pe($data);
        return ['data' => $data, 'endpoint' => $api_endpoint, 'debug' => $debug];
//        return $data;

    }

    public function getCookie($headers){
        $cookie = [];
        if(isset($headers[0]['Set-Cookie'])){
            $h_cookie = explode(';', $headers[0]['Set-Cookie']);
            if($h_cookie AND $h_cookie != ''){
                foreach($h_cookie as $cookie_item){
                    $cookie_item = trim($cookie_item);
                    $c_item = explode('=', $cookie_item);
                    $cookie[$c_item[0]] = isset($c_item[1]) ? $c_item[1] : '';
                }
            }
        }
        return $cookie;
    }

    public function setSession($cookie){
//        d::ajax($cookie['front_s']);
        $_SESSION['front_s'] = $cookie['front_s'];
    }

    public function getHeaders($response){
        $headers = array();

        // Split the string on every "double" new line.
        $arrRequests = explode("\r\n\r\n", $response);

        // Loop of response headers. The "count() -1" is to
        //avoid an empty row for the extra line break before the body of the response.
        $double_key = 0;
        for ($index = 0; $index < count($arrRequests) -1; $index++) {

            foreach (explode("\r\n", $arrRequests[$index]) as $i => $line)
            {
                if ($i === 0)
                    $headers[$index]['http_code'] = $line;
                else
                {
                    list ($key, $value) = explode(': ', $line);
                    if(array_key_exists($key, $headers[$index])){
                        $headers[$index][$key . $double_key] = $value;
                        $double_key++;
                    }else{
                        $headers[$index][$key] = $value;
                    }
                }
            }
        }

//        d::ajax(explode("\r\n", $arrRequests[0]));
//        d::ajax($headers);

        return ['info' => $headers, 'body' => $arrRequests[1]];
    }

//    private function getFullUrl(array $args=[]){
//        $result_url = '';
////        d::pe($args);
//        if(count($args)){
//            if(is_string($args['endpoint']) AND $args['endpoint'] != ''){
//                d::pe('str');
//                if(
//                    isset($args['options']['url_items'])
//                    AND is_array($args['options']['url_items'])
//                    AND count($args['options']['url_items'])
//                ){
//                    $url_items = implode('/',$args['options']['url_items']);
//                    $result_url = $this->base_url.'/'.$args['endpoint'].'/'.$url_items;
//                }else{
//                    $result_url = $this->base_url.'/'.$args['endpoint'];
//                }
//            }elseif(is_array($args['endpoint'])){
//                d::pe('arr');
//                $result_url = $args['endpoint']['base_url'];
//            }else{
//                d::pe('else');
//                $result_url = $this->base_url;
//            }
//        }
//        d::pe($result_url);
//        return $result_url;
//    }

}//Class

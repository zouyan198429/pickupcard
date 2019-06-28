<?php

namespace App\Services;

use GuzzleHttp\Client;
/**
 * 通用http请求
 */
class HttpRequest
{

    /**
     * 调用速通接口服务方法-是什么内容，返回什么内容
     *
     * @param string $url 请求地址
     * @param array/object $params 参数数组/对象
     * @param array $urlParams url地址后面的参数数组 数据最终转换成-如:'?id='
     * @param string $type 请求类型 'GET'、'POST'、'PUT'、'PATCH'、'DELETE'
     * @return array/string
     */
    public static function sendHttpRequest($url, $params = [], $urlParams = [], $type = 'POST')
    {

        if (! empty($urlParams)) {
            $url .= '?' . http_build_query($urlParams);
        }

        // 9位自增编号（每天重置）
        // $number         = ToolsHelper::createSnDaily('BSuTong', 9);
        // $dataExchangeId = date('Ymd') . $number;
        // $logParams = self::getSimpleParams($params);

        // Yii::info([$dataExchangeId, $method, $logParams, $url], 'curl\BSuTongHttp\request');

        $http = new Client();

        switch ($type) {
            case 'POST':
                $result = $http->post($url, ['json' => $params]);
                break;
            case 'GET':
                $result = $http->get($url);
                break;
        }

        if (200 != $result->getStatusCode()) {
            throws('速通：请求失败 StatusCode: ' . $result->getStatusCode());
        }

        $ret = $result->getBody()->getContents();

        // Yii::info([$dataExchangeId, $method, $ret], 'curl\BSuTongHttp\response');

        return $ret;
    }

    /**
     * 调用速通接口服务方法-是什么内容，返回什么内容
     *
     * @param string $url 请求地址
     * @param array/object $params 参数数组/对象
     * @param array $urlParams url地址后面的参数数组 数据最终转换成-如:'?id='
     * @param string $type 请求类型 'GET'、'POST'、'PUT'、'PATCH'、'DELETE'
     * @return mixed array 正常数据
     */
    public static function HttpRequestApi($url, $params = [], $urlParams = [], $type = 'POST')
    {
        $result = self::sendHttpRequest($url, $params, $urlParams, $type);

        $resultData = json_decode($result, true);
        $code = $resultData['code'] ?? 0;
        $msg = $resultData['msg'] ?? '返回数据错误!';
        $data = $resultData['data'] ?? [];
        if ($code == 0){
            throws($msg);
        }

        return $data;
    }

}

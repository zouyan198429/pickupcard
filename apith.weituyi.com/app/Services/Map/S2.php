<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-01-21
 * Time: 11:33
 */

namespace App\Services\Map;


use S2\S2LatLng;

class S2
{
    /**
     * 2.1 计算地球上两个点之间的距离
     * @param $lat1 纬度1
     * @param $lng1 经度1
     * @param $lat2 纬度2
     * @param $lng2 经度2
     * @return float 单位(米) 708  整数四舍五入
     */
    public static function  getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $s2LatLng = S2LatLng::fromDegrees($lat1, $lng1);
        $s2LatLngB = S2LatLng::fromDegrees($lat2, $lng2);
        $s2Point = $s2LatLngB->toPoint();
        $earthDistance = $s2LatLng->getEarthDistance(new S2LatLng($s2Point)); //单位为m
        //精度
        $earthDistance = round($earthDistance * 10000)/10000;

        return  round($earthDistance);
    }
}
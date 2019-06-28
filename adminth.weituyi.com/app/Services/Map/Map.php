<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-01-16
 * Time: 17:27
 */

namespace App\Services\Map;


use App\Services\Tool;

class Map
{
    //ps:赤道半径 6378.137km
    //平均地球半径 6371.004km

    /**
     *长度单位转换 1公里以下不变，以上转为KM [2位小数]
     *
     *@param int  $distance 距离 ：单位：(米)
     *@param int  $decimals 小数点位数 默认 2;>1公里时
     *@return string 1公里以下不变，以上转为KM [2位小数]
     */
     public static function distanceShow($distance, $decimals){
         if($distance < 1000) return $distance . 'm';
         return round($distance/1000, $decimals) . 'km'; //10.46
     }

    /**
     *对数据进行经纬度处理 string 1公里以下不变，以上转为KM [2位小数]
     *
     *@param array  $dataList 需要处理的数据 一维/二维数组 ; 如果排序的话，排序后会对数组进行 array_values 操作
     *@param float  $latitude 纬度
     *@param float  $longitude 经度
     *@param string  $dataUboundName 距离加入数组数据中的下标名称  如 distance
     *@param float  $correctionDistance 矫正距离 单位:米 可以为正数或负数 如 0
     *@param float  $distanceOrder 距离排序 '': 不排序 ; desc 降序  asc: 升序
     *@param string  $latitudeUbound 数据中纬度下标
     *@param string  $longitudeUbound 数据中经度下标
     *@param string  $notLatLonStr 数据中不存在经纬度时，显示的文字  [默认]空'' 或 未知
     *@return  array 二维数组
     */
    public static function resolveDistance(&$dataList, $latitude, $longitude, $dataUboundName = 'distance', $correctionDistance = 0, $distanceOrder = '', $latitudeUbound = 'latitude', $longitudeUbound= 'longitude', $notLatLonStr = ''){
        if(empty($latitude)  || empty($longitude)) return $dataList;

        $isMultiArr = false; // true:二维;false:一维
        foreach($dataList as $k => $v){
            if(is_array($v)){
                $isMultiArr = true;
            }
            break;
        }
        // 一维
        if(!$isMultiArr) $dataList = [$dataList];

        $lastList = [];
        $maxDistance = 0;
        foreach($dataList as $k => $v){
            $temLatitude = $v[$latitudeUbound] ?? 0;
            $temLongitude = $v[$longitudeUbound] ?? 0;
            if( ($temLatitude == 0 && $temLongitude == 0) ){
                $lastList[$k] = $v;
                unset($dataList[$k]);
                continue;
            }
            // $earthDistance = S2::getDistance($latitude, $longitude, $temLatitude, $temLongitude);
            // $earthDistance = Map::getDistanceM($longitude, $latitude, $temLongitude, $temLatitude);

            $earthDistance = static::getDistance($latitude, $longitude, $temLatitude, $temLongitude);
//            $dataList[$k]['distance'] = $earthDistance + 400;
//            $dataList[$k]['distanceStr'] =static::distanceShow($earthDistance, 2);
            if($earthDistance > 1000)  $earthDistance = $earthDistance + $correctionDistance;// 400
            $dataList[$k][$dataUboundName] = $earthDistance;// 400
            $dataList[$k][$dataUboundName . 'Str'] =static::distanceShow($earthDistance, 2);
            if($maxDistance < $earthDistance ) $maxDistance = $earthDistance;
        }

        $temDistance = [
//            'distance' => $maxDistance + 1,
//            'distanceStr' => $notLatLonStr,
            $dataUboundName => $maxDistance + 1,
            $dataUboundName . 'Str' => $notLatLonStr,
        ];
        foreach($lastList as $k => $v){
            $dataList[$k] = array_merge($v, $temDistance);
        }

        // 排序处理
        if(in_array($distanceOrder, ['asc', 'desc'])){
            $dataList = array_values($dataList);
            $orderDistance = [
                 // ['key' => 'distance', 'sort' => $distanceOrder, 'type' => 'numeric'],
                ['key' => $dataUboundName, 'sort' => $distanceOrder, 'type' => 'numeric'],
                 // ['key' => 'id', 'sort' => 'desc', 'type' => 'numeric'],
            ];
            if(!empty($dataList)) {
                $dataList = Tool::php_multisort($dataList, $orderDistance);
                $dataList = array_values($dataList);
               //  $dataList = [1,2,3];
            }
        }

        if(!$isMultiArr) $dataList = $dataList[0] ?? [];// 是一维数组
        return $dataList;
    }

    /**
     *对数据进行经纬度处理 string 1公里以下不变，以上转为KM [2位小数]
     *
     *@param array  $dataInfo 需要处理的数据 一维数组
     *@param float  $latitude 纬度
     *@param float  $longitude 经度
     *@param string  $dataUboundName 距离加入数组数据中的下标名称  如 distance
     *@param float  $correctionDistance 矫正距离 单位:米 可以为正数或负数 如 0
     *@param float  $distanceOrder 距离排序 '': 不排序 ; desc 降序  asc: 升序
     *@param string  $latitudeUbound 数据中纬度下标
     *@param string  $longitudeUbound 数据中经度下标
     *@param string  $notLatLonStr 数据中不存在经纬度时，显示的文字  [默认]空'' 或 未知
     *@return  array 二维数组
     */
    public static function resolveDistanceSingle(&$dataInfo, $latitude, $longitude, $dataUboundName = 'distance', $correctionDistance = 0, $distanceOrder = '', $latitudeUbound = 'latitude', $longitudeUbound= 'longitude', $notLatLonStr = '')
    {
        $dataList = [$dataInfo];
        static::resolveDistance($dataList, $latitude, $longitude, $dataUboundName, $correctionDistance, $distanceOrder, $latitudeUbound, $longitudeUbound, $notLatLonStr);
        $dataInfo = $dataList[0] ?? [];
        return $dataInfo;
    }

    /**
     *计算某个经纬度的周围某段距离的正方形的四个点
     *
     *@param lng float 经度
     *@param lat float 纬度
     *@param distance float 该点所在圆的半径，该圆与此正方形内切，默认值为0.5千米
     *@return array 正方形的四个点的经纬度坐标
     */
    public static function returnSquarePoint($lng, $lat,$distance = 0.5){
        // define(EARTH_RADIUS, 6371);//地球半径，平均半径为6371km
        // $dlng = 2 * asin(sin($distance / (2 * EARTH_RADIUS)) / cos(deg2rad($lat)));
        // $dlng = rad2deg($dlng);

        // $dlat = $distance/EARTH_RADIUS;

        $earthRadius = 6371;//地球半径，平均半径为6371km
        $dlng = 2 * asin(sin($distance / (2 * $earthRadius)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);

        $dlat = $distance/$earthRadius;
        $dlat = rad2deg($dlat);

        return array(
            'left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
            'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
            'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
            'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
        );
    }

    // 根据经纬度，返回不同的字符长度 geoHash
    // $geohash 有值，则，只格式化传入的 $geohash值
    public static function getGeoHashs($lat = '', $log = '', $geohash= ''){
        if(empty($geohash)) {// 取得原始GEOHASH wwe0x0euu12h
            if($lat == '' || $log == '') return [];
            if ( ! function_exists('geohash_encode')){
                $geohash = static::encode_geohash($lat, $log, 12);
            }else{
                $geohash = geohash_encode($lat, $log, 12); // 需要安装php扩展 geohash.so https://github.com/shenzhe/geohash
            }
        }

        $arr['0']=$geohash;//原始HASH    宽度	高度
        $arr['12']=substr($geohash,0,12);// ≤ 37.2mm	×	18.6mm
        $arr['11']=substr($geohash,0,11);// ≤ 149mm	×	149mm
        $arr['10']=substr($geohash,0,10);// ≤ 1.19m	×	0.596m
        $arr['9']=substr($geohash,0,9); // ≤ 4.77m	×	4.77m  ;9位hash 距离最精确，--用来确定是否相遇
        $arr['8']=substr($geohash,0,8);// ≤ 38.2m	×	19.1m  8位，距离相对精确，具体精度看上面的表。
        $arr['7']=substr($geohash,0,7);// ≤ 153m	×	153m ; 7位，距离也挺精确
        $arr['6']=substr($geohash,0,6);// ≤ 1.22km	×	0.61km    --和周边8个geoHash，可以查周边0.5-0.61公里的人事物
        $arr['5']=substr($geohash,0,5);// ≤ 4.89km	×	4.89km    --和周边8个geoHash，可以查周边0.6-4.89公里的人事物
        $arr['4']=substr($geohash,0,4);// ≤ 39.1km	×	19.5km ;4位hash,只要前4位相同，可以找出附近20KM的人事物。  --和周边8个geoHash，可以查周边4.89-19.5公里的人事物
        $arr['3']=substr($geohash,0,3);// ≤ 156km	×	156km --和周边8个geoHash，可以查周边19.5-156公里的人事物
        $arr['2']=substr($geohash,0,2);// ≤ 1,250km	×	625km --和周边8个geoHash，可以查周边156-625公里的人事物
        $arr['1']=substr($geohash,0,1);// ≤ 5,000km	×	5,000km
        return $arr;
    }
    //~~~~~~~~~~~~~~~~~~~~~~~~根据经纬度计算两点之间的距离~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    /**
     * [PHP Code] 根据经纬度计算两点之间的记录 --- 余弦定理以及弧度计算方法
     * @param $lat1 纬度1
     * @param $lng1 经度1
     * @param $lat2 纬度2
     * @param $lng2 经度2
     * @return float 单位(米) 708  整数四舍五入
     */
    public static function  getDistance($lat1, $lng1, $lat2, $lng2)
    {
        //地球半径
        $R = 6378137;

        //将角度转为弧度
        $radLat1 = deg2rad($lat1);
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);

        //结果
        $s = acos(cos($radLat1) * cos($radLat2) * cos($radLng1 - $radLng2)
                + sin($radLat1) * sin($radLat2)) * $R;

        //精度
        $s = round($s * 10000)/10000;

        return  round($s);
    }
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    /*
    * 根据两点间的经纬度计算距离 ---单位:米   Google公开的距离计算
    * @param $lng1 经度
    * @param $lat1 纬度
    * @param $lng2 经度
    * @param $lat2 纬度
    * @return int 单位:米  707.88856935257
    */
    public static function getDistanceM($lng1, $lat1, $lng2, $lat2)
    {
        //将角度转为狐度
        $radLat1 = deg2rad($lat1);//deg2rad()函数将角度转换为弧度
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);
        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;
        return $s;
    }
    //~~~~~~~~~根据经纬度计算俩点间的距离PHP版~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    /**
     * @param $d
     * @return float
     * 转换弧度
     */
    public static function rad($d)
    {
        return $d * pi() / 180.0;
    }

    /**
     * @param $lat1纬度
     * @param $lng1经度
     * @param $lat2纬度
     * @param $lng2经度
     * @return float|int
     * 计算距离(KM) 0.7079 单位千米 四位小数
     */
    public static function getDistanceKM($lat1, $lng1, $lat2, $lng2)
    {
        $EARTH_RADIUS = 6378.137;
        $radLat1 = static::rad($lat1);
        $radLat2 = static::rad($lat2);
        $a = $radLat1 - $radLat2;
        $b = static::rad($lng1) - static::rad($lng2);
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) +
                cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $s = $s * $EARTH_RADIUS;
        $s = round($s * 10000) / 10000;
        return $s;
    }

    //~~~~~~~~~php实现geohash算法~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    // 获得geohash串
    public static function encode_geohash($latitude, $longitude, $deep = 12)
    {
        $BASE32 = '0123456789bcdefghjkmnpqrstuvwxyz';
        $bits = array(16, 8, 4, 2, 1);
        $lat = array(-90.0, 90.0);
        $lon = array(-180.0, 180.0);

        $bit = $ch = $i = 0;
        $is_even = 1;
        $i = 0;
        $geohash = '';
        while ($i < $deep) {
            if ($is_even) {
                $mid = ($lon[0] + $lon[1]) / 2;
                if ($longitude > $mid) {
                    $ch |= $bits[$bit];
                    $lon[0] = $mid;
                } else {
                    $lon[1] = $mid;
                }
            } else {
                $mid = ($lat[0] + $lat[1]) / 2;
                if ($latitude > $mid) {
                    $ch |= $bits[$bit];
                    $lat[0] = $mid;
                } else {
                    $lat[1] = $mid;
                }
            }

            $is_even = !$is_even;
            if ($bit < 4)
                $bit++;
            else {
                $i++;
                $geohash .= $BASE32[$ch];
                $bit = 0;
                $ch = 0;
            }
        }
        return $geohash;
    }

    // 解析geoHash获得中心坐标[0=> lat经度，1 => log经度]
    public static function decode_geohash($geohash)
    {
        $geohash = strtolower($geohash);
        $BASE32 = '0123456789bcdefghjkmnpqrstuvwxyz';
        $bits = array(16, 8, 4, 2, 1);
        $lat = array(-90.0, 90.0);
        $lon = array(-180.0, 180.0);
        $hashlen = strlen($geohash);
        $is_even = 1;

        for ($i = 0; $i < $hashlen; $i++) {
            $of = strpos($BASE32, $geohash[$i]);
            for ($j = 0; $j < 5; $j++) {
                $mask = $bits[$j];
                if ($is_even) {
                    $lon[!($of & $mask)] = ($lon[0] + $lon[1]) / 2;
                } else {
                    $lat[!($of & $mask)] = ($lat[0] + $lat[1]) / 2;
                }
                $is_even = !$is_even;
            }
        }
        $point = array(0 => ($lat[0] + $lat[1]) / 2, 1 => ($lon[0] + $lon[1]) / 2);
        return $point;
    }


}
<?php

namespace App\Services;
use App\Services\Lock\RedisesLock;
use App\Services\Lock\RedisLock;
use App\Services\Request\CommonRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

/**
 * 通用工具服务类
 */
class Tool
{

    /**
     * HTTP Protocol defined status codes
     * HTTP协议状态码,调用函数时候只需要将$num赋予一个下表中的已知值就直接会返回状态了。
     * @param int $num
     *
     */
    public static function https($num) {
        $http = array (
            100 => "HTTP/1.1 100 Continue",
            101 => "HTTP/1.1 101 Switching Protocols",
            200 => "HTTP/1.1 200 OK",
            201 => "HTTP/1.1 201 Created",
            202 => "HTTP/1.1 202 Accepted",
            203 => "HTTP/1.1 203 Non-Authoritative Information",
            204 => "HTTP/1.1 204 No Content",
            205 => "HTTP/1.1 205 Reset Content",
            206 => "HTTP/1.1 206 Partial Content",
            300 => "HTTP/1.1 300 Multiple Choices",
            301 => "HTTP/1.1 301 Moved Permanently",
            302 => "HTTP/1.1 302 Found",
            303 => "HTTP/1.1 303 See Other",
            304 => "HTTP/1.1 304 Not Modified",
            305 => "HTTP/1.1 305 Use Proxy",
            307 => "HTTP/1.1 307 Temporary Redirect",
            400 => "HTTP/1.1 400 Bad Request",
            401 => "HTTP/1.1 401 Unauthorized",
            402 => "HTTP/1.1 402 Payment Required",
            403 => "HTTP/1.1 403 Forbidden",
            404 => "HTTP/1.1 404 Not Found",
            405 => "HTTP/1.1 405 Method Not Allowed",
            406 => "HTTP/1.1 406 Not Acceptable",
            407 => "HTTP/1.1 407 Proxy Authentication Required",
            408 => "HTTP/1.1 408 Request Time-out",
            409 => "HTTP/1.1 409 Conflict",
            410 => "HTTP/1.1 410 Gone",
            411 => "HTTP/1.1 411 Length Required",
            412 => "HTTP/1.1 412 Precondition Failed",
            413 => "HTTP/1.1 413 Request Entity Too Large",
            414 => "HTTP/1.1 414 Request-URI Too Large",
            415 => "HTTP/1.1 415 Unsupported Media Type",
            416 => "HTTP/1.1 416 Requested range not satisfiable",
            417 => "HTTP/1.1 417 Expectation Failed",
            500 => "HTTP/1.1 500 Internal Server Error",
            501 => "HTTP/1.1 501 Not Implemented",
            502 => "HTTP/1.1 502 Bad Gateway",
            503 => "HTTP/1.1 503 Service Unavailable",
            504 => "HTTP/1.1 504 Gateway Time-out"
        );
        header($http[$num]);
    }

    /**
     * 取得IP
     *
     *
     * @return string 字符串类型的返回结果
     */
    public static function getIp(){
        if (@$_SERVER['HTTP_CLIENT_IP'] && $_SERVER['HTTP_CLIENT_IP']!='unknown') {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (@$_SERVER['HTTP_X_FORWARDED_FOR'] && $_SERVER['HTTP_X_FORWARDED_FOR']!='unknown') {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return preg_match('/^\d[\d.]+\d$/', $ip) ? $ip : '';
    }


    /**
     * 获取文件列表(所有子目录文件)
     *
     * @param string $path 目录
     * @param array $file_list 存放所有子文件的数组
     * @param array $ignore_dir 需要忽略的目录或文件
     * @return boolean 数据格式的返回结果
     */
    public static function readFileList($path,&$file_list,$ignore_dir=array()){
        $path = rtrim($path,'/');
        if (is_dir($path)) {
            $handle = @opendir($path);
            if ($handle){
                while (false !== ($dir = readdir($handle))){
                    if ($dir != '.' && $dir != '..'){
                        if (!in_array($dir,$ignore_dir)){
                            if (is_file($path.DS.$dir)){
                                $file_list[] = $path.DS.$dir;
                            }elseif(is_dir($path.DS.$dir)){
                                self::readFileList($path.DS.$dir,$file_list,$ignore_dir);
                            }
                        }
                    }
                }
                @closedir($handle);
                //return $file_list;
            }else {
                return false;
            }
        }else {
            return false;
        }
    }

    /**
     * 生成订单流水号（18位数字）
     * 最大可以支持1分钟1亿订单号不重复
     *
     * @return string $orderSn
     */
    public static function createSn($namespace = 'default', $prefix = '', $length = 8)
    {
        $insertId = Yii::$app->redis->incr('FlowSn:' . ucfirst($namespace));
        $suffix   = self::getSnSuffix();

        return $prefix . date('ymdHi') . str_pad(substr($insertId, -$length), $length, 0, STR_PAD_LEFT) . $suffix;
    }

    /**
     * 产生随机字符串
     *
     * @param int $length
     *
     * @return string
     */
    public static function createRandomStr($length = 32)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';

        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[mt_rand(0, strlen($chars) - 1)];
        }

        return $str;
    }

    /**
     * 生成随机令牌凭证
     *
     * @return string
     */
    public static function buildToken($uniqueId = null)
    {
        return sha1(uniqid($uniqueId) . mt_rand(1, 10000));
    }

    /**
     * 订单号生成器
     * @param int $uid 用户id
     * @return int
     */
     public static function order_sn($uid)
    {
        // return '619' . date('YmdHis') . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT) . $uid;
        //return date('YmdHis') . str_pad(mt_rand(1, 99), 2, '0', STR_PAD_LEFT)
        //    . str_pad($uid,4,"0",STR_PAD_LEFT);
        return date('ymdHis') . str_pad(mt_rand(1, 99), 2, '0', STR_PAD_LEFT);
           // . str_pad($uid,4,"0",STR_PAD_LEFT);
    }

    /**
     * ShopNC 生成订单编号
     * @return string
     */
    public static function snOrder() {
        // $recharge_sn = date('Ymd').substr( implode(NULL,array_map('ord',str_split(substr(uniqid(),7,13),1))) , -8 , 8);
        $recharge_sn = date('ymd').substr( implode(NULL,array_map('ord',str_split(substr(uniqid(),7,13),1))) , -8 , 8);
        return $recharge_sn;
    }

    /**
     * 生成订单号
     *在网上找了一番，发现这位同学的想法挺不错的，redtamo，具体的请稳步过去看看，
     * 我作简要概述，该方法用上了英文字母、年月日、Unix 时间戳和微秒数、随机数，重复的可能性大大降低，还是很不错的。
     * 使用字母很有代表性，一个字母对应一个年份，总共16位，不多也不少.
     *
     * @return string
     */
    public static function createOrder(){
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        return $orderSn;
    }


    /**
     * 生成订单流水号（18位数字）+ 前部会含 8位日期时间
     *
     * @param string $namespace redis记数器标识键 ；因为有redis锁，可以考虑把用户后两位做为用户分流，就分成100流了，注意在订单前缀/后缀加上分流的用户后两位。
     * @param array $fixParams
    $fixParams = [
    'prefix' => '',// 前缀[1-2位] 可填;可写业务编号等
    'midFix' => '',// 日期后中间缀[1-2位] 可填;适合用户编号里的后2位或其它等
    'backfix' => '',// 后缀[1-2位] 可填;备用
    'expireNums' => [],// redis设置缓存 ，在两个值之间时 - 二维 [[1,20,'缓存的秒数365 * 24 * 60 * 60'], [40,50,'缓存的秒数']]
    'needNum' => 0,// 需要拼接的内容 1 年 2日期[一年中的第几个分钟[内-向上取整]] 4 自定义日期格式 8 自增的序号
    'dataFormat' => '', // needNum 值为 4时的日期格式  'YmdHis'
    // Y: 年，四位数字  y: 年，两位数字
    // n: 月份，两位数字，不补零；从"1"至"12"  m 数字表示的月份，有前导零 01 到 12
    //z 年份中的第几天 0 到 365
    // d: 几日，两位数字，若不足则补零；从"01"至"31"  j: 几日，不足不被零；从"1"至"31"
    // h: 12小时制的小时，从"01"至"12"
    //  g 小时，12 小时格式，没有前导零 1 到 12  ;
    //  H: 24小时制的小时，从"00"至"23"；
    //  G: 24小时制的小时，不补零；从"0"至"23"
    // i 有前导零的分钟数 00 到 59>

    // s 秒数，有前导零 00 到 59>

    ];
     * @param int $length 字符串长度- 使用以后，只能增，不建议减[这样可以按时间排序] 选择自己适合的体量/每分钟  订单[选4] ,  其它不重要的单号评估一下，一分钟生成的数量，给高/低
     *                                           共用          一个用户保一单/分
     * 1 最大可以支持1分钟10个订单号不重复       10/分          10 * 用户要用的位数(如2位100) = 1千用户
     * 2 最大可以支持1分钟1百个订单号不重复       1百/分        100 *   100  = 1 万+
     * 3 最大可以支持1分钟1千订单号不重复       1千/分          1000 *  100 =  10 万+
     * 4 最大可以支持1分钟1万订单号不重复       1万/分          1万 *  100  =  100 万+
     * 5 最大可以支持1分钟10万订单号不重复       10万/分       10万*  100  =   千万+
     * 6 最大可以支持1分钟百万订单号不重复       百万/分       百万 * 100 =  1亿+
     * 7 最大可以支持1分钟千万订单号不重复       千万/分
     * 8 最大可以支持1分钟1亿订单号不重复       1亿/分
     * 9 最大可以支持1分钟十亿订单号不重复       十亿/分
     * 10 最大可以支持1分钟百亿订单号不重复       百亿/分
     * 11 最大可以支持1分钟千亿订单号不重复       千亿/分
     * 12 最大可以支持1分钟万亿订单号不重复       万亿/分
     * @param string $backfix 后缀[1-2位] 可填
     * @return mixed
     */
    public static function makeOrder($namespace = 'default', $fixParams = [], $length = 6){
        $prefix = $fixParams['prefix'] ?? '';
        $midFix = $fixParams['midFix'] ?? '';
        $backfix = $fixParams['backfix'] ?? '';
        $expireNums = $fixParams['expireNums'] ?? [];// redis设置缓存 ，在两个值之间时 - 二维 [[1,20,'缓存的秒数365 * 24 * 60 * 60'], [40,50,'缓存的秒数']]
        $needNum = $fixParams['needNum'] ?? 0;// 需要拼接的内容 1 年 2日期 4 自增的序号
        $dataFormat = $fixParams['dataFormat'] ?? '';// needNum 值为 4时的日期格式
        // 业务编号(1位0-9); 年(2位 当前-99)  ; 月12--2位	日31--2位(12*31=372 --3位) ; 时24--2位	分60--2位	秒 60--2位 (=86400 --5位)
        if( (($needNum & (1 + 2)) > 0)  ) $year = date('y');// Y: 年，四位数字  y: 年，两位数字

        if(($needNum & 2) == 2){
            $month = date('n');//n: 月份，两位数字，不补零；从"1"至"12"  m 数字表示的月份，有前导零 01 到 12
            $yearDays =((int)  date('z'));// + 1;//z 年份中的第几天 0 到 365
            $day = date('j');// d: 几日，两位数字，若不足则补零；从"01"至"31"  j: 几日，不足不被零；从"1"至"31"
            // h: 12小时制的小时，从"01"至"12"
            //  g 小时，12 小时格式，没有前导零 1 到 12  ;
            //  H: 24小时制的小时，从"00"至"23"；
            //  G: 24小时制的小时，不补零；从"0"至"23"
            $hour = date('G') + 1;
            // i 有前导零的分钟数 00 到 59>
            $minute = ((int) date('i')) + 1;
            // s 秒数，有前导零 00 到 59>
            $second = ((int) date('s')) + 1;
            //一年中的第几个分钟[内] 月*日*时 12*31*24=8928--4位
            $mdh = $yearDays * 24 * 60 + $minute;
//        echo '$year = ' . $year . ';$month = ' . $month  . ';$yearDays = ' . $yearDays . ';$day = ' . $day . ';$hour = ' . $hour . ';$minute = ' . $minute . ';$second = ' . $second . '<br/>';
//        echo '$mdh = ' . $mdh . '<br/>';
        }


        $lockObj = Tool::getLockRedisesLaravelObj();
        $lockState = $lockObj->lock('lock:' . Tool::getUniqueKey([Tool::getActionMethod(), __CLASS__, __FUNCTION__, $namespace, $fixParams]), 2000, 2000);//加锁
        if($lockState)
        {
            try {
                $redisKey = 'FlowSn:' . ucfirst($namespace);
                $insertId = Redis::incr($redisKey);
                foreach($expireNums as $v){
                    if(count($v) < 3) continue;
                    $orderNums = [$v[0], $v[1]];
                    $orderNums = array_values($orderNums);
                    sort($orderNums);
                    if($insertId >= $orderNums[0] && $insertId <= $orderNums[1]) Redis::expire($redisKey, $v[2] );  #设置过期时间 单位秒数 一年  365 * 24 * 60 * 60
                }
            } catch ( \Exception $e) {
                throws($e->getMessage(), $e->getCode());
            }finally{
                $lockObj->unlock($lockState);//解锁
            }
        }else{
            throws('生成单号有错，请稍后重试!');
        }

        $orderNum = $prefix;// 前缀
        if(($needNum & 1) == 1) $orderNum .= $year;// 年2位

        // 到一年的第几分钟 6位
        if(($needNum & 2) == 2) $orderNum .= str_pad(substr($mdh, -6), 6, '0', STR_PAD_LEFT);

        // needNum 值为 4时的日期格式
        if(($needNum & 4) == 4 && (!empty($dataFormat))) $orderNum .= date($dataFormat);//

        $orderNum .= $midFix;// 中缀

        // 8 自增的序号
        if(($needNum & 8) == 8) $orderNum .= str_pad(substr($insertId, -$length), $length, 0, STR_PAD_LEFT);

        $orderNum .= $backfix;// 后缀

        //   return $prefix . $year . str_pad(substr($mdh, -6), 6, '0', STR_PAD_LEFT)
        //  . $midFix . str_pad(substr($insertId, -$length), $length, 0, STR_PAD_LEFT) . $backfix;// . $suffix
        return $orderNum;

    }


    /**
     * 获取唯一标识长度,最长37位,默认10位
     *
     * @param int $length 字符串长度
     *
     * @return string
     */
    public static function createUniqueNumber($length = 10)
    {
        return substr(date('YmdHis') . md5(uniqid()), 0, $length);
    }

    /**
     * 根据字符集生成随机字符串
     *
     * @param int $length 字符串长度
     * @param int $type 0:纯数字, 1:数字与字母
     *
     * @return string
     */
    public static function generatePassword($length = 6, $type = 0)
    {
        if ($type == 1) {
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        }
        else {
            $chars = '0123456789';
        }
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[mt_rand(0, strlen($chars) - 1)];
        }

        return $password;
    }


    //----------------- 单个redis锁-------------------
    /**
     * 获得锁对象
     *
     * @param array
    $config =[
    'host' => '',// 默认 localhost
    'port' => '',// 默认 6379
    'auth' => '',// 默认空
    'dbNum' => 0,// 默认 0
    ];
     * @return object
     */
    public static function getLockObjBase($config = []){
        return RedisLock::instance($config);
    }

    /**
     * 获得锁对象--laravel配置的
     *
     * @param array
     * @return object
     */
    public static function getLockLaravelObj(){
        $config =[
            'host' => env('REDIS_HOST', 'localhost'),// 默认 localhost
            'port' => env('REDIS_PORT', 6379),// 默认 6379
            'auth' => env('REDIS_PASSWORD', ''),// 默认空
            // 'dbNum' => env('REDIS_DB', 0),// 默认 0
        ];
        return Tool::getLockObjBase($config);
    }

    //----------------- 分布式锁-多个redis锁---------------

    /**
     * 获得锁对象
     *
     * @param array 二维数组
    $servers = [
    //['127.0.0.1', 6379, 0.01, '', 0],
    ['192.168.56.114', 6379, 0.01, '', 0],
    //['172.29.8.165', 6379, 0.01, '', 0],
    //['127.0.0.1', 6399, 0.01, '', 0],
    ];
     * @return object
     */
    public static function getLockRedisesObjBase($config = []){
        return RedisesLock::instance($config);
    }

    /**
     *
     *
     * 获得锁对象--laravel配置的
     *
     * @param array
     * @return object
     */
    public static function getLockRedisesLaravelObj(){
        $config =[
            [
                'host' => env('REDIS_HOST', 'localhost'),// 默认 localhost
                'port' => env('REDIS_PORT', 6379),// 默认 6379
                'timeout' => 0.01,// 以秒为单位）。默认值为 15 秒。值 0 指示无限制
                'auth' => env('REDIS_PASSWORD', ''),// 默认空
                'dbNum' => env('REDIS_DB', 0),// 默认 0
            ]
        ];
        return Tool::getLockRedisesObjBase($config);
    }


    /**
     * Xml To array
     *
     * @param $data
     *
     * @return array
     */
    public static function xmlToArray($data)
    {
        // 禁止引用外部xml实体
        libxml_disable_entity_loader(true);

        $data = str_ireplace(['encoding="GB2312"', 'encoding="GBK"'], 'encoding="GB18030"', $data);

        // 先把xml转换为simplexml对象，再把simplexml对象转换成 json，再将 json 转换成数组
        try {
            $result = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);

            return $result ? json_decode(json_encode($result), true) : [];
        } catch (\Throwable $e) {
            throws('xml格式不正确：' . $data);
        }
    }

    /**
     * 数组转换成xml
     *
     * @param $arr
     * @param string $root
     * @param string $endroot
     *
     * @return string
     */
    public static function arrayToXml($arr, $root = '<msgdata>', $endroot = '</msgdata>')
    {
        $xml = $root;

        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                if (is_numeric($key)) {
                    $xml .= self::arrayToXml($val, '', '');
                } else {
                    $xml .= '<' . $key . '>' . self::arrayToXml($val, '', '') . '</' . $key . '>';
                }
            }
            else {
                if (is_numeric($val) || $val === '') {
                    $xml .= '<' . $key . '>' . $val . '</' . $key . '>';
                }
                else {
                    $xml .= '<' . $key . '><![CDATA[' . $val . ']]></' . $key . '>';
                }
            }
        }

        $xml .= $endroot;

        return $xml;
    }

    /**
     * 获取xml post请求数据
     *
     * @return bool|mixed
     */
    public static function getXmlPost()
    {
        if (version_compare(PHP_VERSION, '5.6.0', '<')) {
            if (! empty($GLOBALS['HTTP_RAW_POST_DATA'])) {
                $xmlInput = $GLOBALS['HTTP_RAW_POST_DATA'];
            }
            else {
                $xmlInput = file_get_contents('php://input');
            }
        }
        else {
            $xmlInput = file_get_contents('php://input');
        }

        if (empty($xmlInput)) {
            return [];
        }

        // 禁止引用外部xml实体
        libxml_disable_entity_loader(true);

        // 先把xml转换为simplexml对象，再把simplexml对象转换成 json，再将 json 转换成数组
        return json_decode(json_encode(simplexml_load_string($xmlInput, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /**
     * 保存redis值-json/序列化保存
     * @param string 必填 $pre 前缀
     * @param string $key 键 null 自动生成
     * @param string 选填 $value 需要保存的值，如果是对象或数组，则序列化
     * @param int 选填 $expire 有效期 秒 <=0 长期有效
     * @param int 选填 $operate 操作 1 转为json 2 序列化 3 不转换
     * @return $key
     * @author zouyan(305463219@qq.com)
     */
    public static function setRedis($pre = '', $key = null, $value = '', $expire = 0, $operate = 1)
    {
        if(empty($key)){
            $key = self::createUniqueNumber(25);
        }
        $key = $pre . $key;
        // 序列化保存
        try{
            switch($operate){
                case 1:
                    if(is_array($value)){
                        $value = json_encode($value);
                    }
                    break;
                case 2:
                    $value = serialize($value);
                    break;
                default:
                    break;
            }
            if(is_numeric($expire) && $expire > 0){
                Redis::setex($key, $expire, $value);
            }else{
                Redis::set($key, $value);
            }
        } catch ( \Exception $e) {
            throws('redis[' . $key . ']保存失败；信息[' . $e->getMessage() . ']');
        }
        return $key;
    }

    /**
     * 获得key的redis值
     * @param string $key 键
     * @param int 选填 $operate 操作 1 转为json 2 序列化 ; 3 不转换
     * @return max $value  ; false失败
     * @author zouyan(305463219@qq.com)
     */
    public static function getRedis($key, $operate = 1)
    {
        $value = Redis::get($key);
        if(is_bool($value) || is_null($value)){//string或BOOL 如果键不存在，则返回 FALSE。否则，返回指定键对应的value值。
            return false;
        }
        switch($operate){
            case 1:
                if (!self::isNotJson($value)) {
                    $value = json_decode($value, true);
                }
                break;
            case 2:
                $value = unserialize($value);
                break;
            default:
                break;
        }
        return $value;

    }
    /**
     * 获得key的redis值
     * @param string $key 键
     * @return $value
     * @author zouyan(305463219@qq.com)
     */
    public static function delRedis($key)
    {
        return Redis::del($key);
    }


    //判断数据不是JSON格式:
    public static function isNotJson($str){
        return is_null(json_decode($str));
    }

    // 保存session

    /**
     * 保存redis值-json/序列化保存
     * @param string 必填 $pre 前缀
     * @param string $key 键 null 自动生成
     * @param string 选填 $value 需要保存的值，如果是对象或数组，则序列化
     * @param int 选填 $expire 有效期 秒 <=0 长期有效
     * @param int 选填 $operate 操作 1 转为json 2 序列化
     * @return $key
     * @author zouyan(305463219@qq.com)
     */
    /**
     * 保存session值-json/序列化保存 注意如果是session，一定要确保前面有 session_start(); // 初始化session
     * @param string $key_pre 前缀
     * @param string 选填 $value 需要保存的值，如果是对象或数组，则序列化
     * @param boolean 选填 $save_session 是否保存session true:键保存到session.false，只返回key，给小程序用
     * @param string 选填 $session_key  如果保存的session，session的键名
     * @param int 选填 $expire 有效期 秒 <=0 长期有效 60*60*24*1
     * @param int 选填 $operate 操作 1 转为json 2 序列化
     * @return $redisKey  数据在redis中的键值
     * @author zouyan(305463219@qq.com)
     */
    public static function setLoginSession($key_pre= 'login', $value = '', $save_session = true, $session_key = 'loginKey', $expire = 0, $operate = 1)
    {
        $key = null;// 键名
        $pre = '';// 前缀
        $need_save_key = false; // 是否需要重新获得key
        if($save_session){
            if (!session_id()) session_start();
            $key = $_SESSION[$session_key] ?? '';
            if(empty($key)){
                $key = null;
                $need_save_key = true;
            }
        }
        // 没有key则加前缀
        if(empty($key)){
            $pre = $key_pre;
        }

        $redisKey = self::setRedis($pre, $key, $value, $expire , $operate); // 1天

        // key有变化
        if($save_session && $need_save_key){
            if (!session_id()) session_start();
            $_SESSION[$session_key] = $redisKey;
        }
        return $redisKey;
    }

    // 获得session

    /**
     * 获得key的值 注意如果是session，一定要确保前面有 session_start(); // 初始化session
     * @param string $redisKey 全键[含前缀],小程序传入的 $save_session 为 true时，可以传null
     * @param boolean 选填 $save_session 是否保存session true:键保存到session.false，只返回key，给小程序用
     * @param string 选填 $session_key  如果保存的session，session的键名
     * @param int 选填 $operate 操作 1 转为json 2 序列化
     * @return $value redis中保存的数据
     * @author zouyan(305463219@qq.com)
     */
    public static function getSession($redisKey = null, $save_session = true, $session_key = 'loginKey', $operate = 1)
    {
        if($save_session){
            if (!session_id()) session_start();
            $redisKey = $_SESSION[$session_key] ?? '';
        }
        $val = '';
        if(!empty($redisKey)){
            $val = self::getRedis($redisKey, $operate);
        }else{
            // throws('参数redisKey不能为空!');
        }
        return $val;
    }
    /**
     * 获得key的值 注意如果是session，一定要确保前面有 session_start(); // 初始化session
     * @param string $redisKey 全键[含前缀],小程序传入的 $save_session 为 true时，可以传null
     * @param boolean 选填 $save_session 是否保存session true:键保存到session.false，只返回key，给小程序用
     * @param string 选填 $session_key  如果保存的session，session的键名
     * @return boolean true:成功 ;false:失败
     * @author zouyan(305463219@qq.com)
     */
    public static function delSession($redisKey = null, $save_session = true, $session_key = 'loginKey')
    {
        if($save_session){
            if (!session_id()) session_start();
            $redisKey = $_SESSION[$session_key] ?? '';
        }

        if($save_session && isset($_SESSION[$session_key])){
            unset($_SESSION[$session_key]); //保存某个session信息
        }
        return self::delRedis($redisKey); // 删除redis中的值
    }

    // 数组操作
    /**
     * 二维数组中每个一维数组追加指定的一维数组值
     *
     * @param array $dataList 源数据 二维数组
     * @param array $appendArr 需要追加的一维数据 一维数组   ['is_multi' => 0, 'is_must' => 1]
     * @return array
     */
    public static function arrAppendKeys(&$dataList, $appendArr){
        foreach($dataList as $k => $v){
            $v = array_merge($v, $appendArr);
            $dataList[$k] = $v;
        }
        return $dataList;
    }

    /**
     * 一维数组清除空值
     *
     * @param array $array
     * @return array
     */
    public static function arrClsEmpty(&$array){
        foreach($array as $k => $v){
            if(is_null($v) || trim($v) === '') unset($array[$k]);
        }
        return $array;
    }

    /**
     * 返回以原数组某个值为下标的新数组
     *
     * @param array $array
     * @param string $key
     * @param int $type 1一维数组2二维数组
     * @return array
     */
    public static function arrUnderReset($array, $key, $type = 1){
        if (is_array($array)){
            $tmp = [];
            foreach ($array as $v) {
                if ($type === 1){
                    $tmp[$v[$key]] = $v;
                }elseif($type === 2){
                    $tmp[$v[$key]][] = $v;
                }
            }
            return $tmp;
        }else{
            return $array;
        }
    }


    /**
     * 二维数组指定下标的值为下标,指定下标的值为值，的一维数组
     *
     * @param array $array 二维数组
     * @param string $uboundkey 值做为新数组的键的下标
     * @param string $uboundValKey 值做为新数组的键的下标
     * @return array 一维数组
     */
    public static function formatArrKeyVal($array, $keyUbound, $valUbound){
        $reArr = [];
        if (! is_array($array)) return $reArr;
        foreach ($array as $v) {
            if( !isset($v[$keyUbound]) || !isset($v[$valUbound])) continue;
            $reArr[$v[$keyUbound]] = $v[$valUbound];
        };
        return $reArr;
    }

    /**
     * 一维数组返回指定下标数组的一维数组,-以原数组下标不准，
     *
     * @param array $array 一维数组
     * @param array $keys 要获取的下标数组 -维 [ '新下标名' => '原下标名' ]
     * @param boolean $needNotIn  keys在数组中不存在的，false:不要，true：空值
     * @return array 一维数组
     */
    public static function formatArrKeys(&$array, $keys, $needNotIn = false){
        $newArr = [];
        foreach($keys as $new_k => $old_k){
            if(!isset($array[$old_k])){// 不存在
                if($needNotIn){// true：空值
                    $newArr[$new_k] = '';
                }
            }else{// 存在
                $newArr[$new_k] = $array[$old_k];
            }
        }
        $array = $newArr;
        return $newArr;
    }

    /**
     * 二维数组返回指定下标数组的新的二维维数组,-以原数组下标为准，
     *
     * @param array $array 二维数组
     * @param array $keys 要获取的下标数组 -维[ '新下标名' => '原下标名' ]
     * @param boolean $needNotIn  keys在数组中不存在的，false:不要，true：空值
     * @return array 一维数组
     */
    public static function formatTwoArrKeys(&$array, $keys, $needNotIn = false){
        foreach($array as $k => $v){
            self::formatArrKeys($array[$k], $keys, $needNotIn );
        }
        return $array;
    }

    /**
     * 一维数组转换为键值相同的一维数组
     *
     * @param array $array 一维数组
     * @param boolean $equalType  统计的类型，false:以键为标准，true：以值为标准
     * @return array 一维数组
     */
    public static function arrEqualKeyVal($array,  $equalType = true){
        $reArr = [];
        foreach($array as $k => $v){
            if($equalType){
                $reArr[$v] = $v;
            }else{
                $reArr[$k] = $k;
            }
        }
        return $reArr;
    }

    /**
     * 获得当前的路由和方法
     *
     * @return string 当前的路由和方法  App\Http\Controllers\CompanyWorkController@addInit
     */
    public static function getActionMethod(){
        return \Route::current()->getActionName();
    }

    /**
     * 获得缓存数据
     * @param string $pre 键前缀 __FUNCTION__
     * @param string $cacheKey 键
     * @param array $paramKeyValArr 会作为键的关键参数值数组 --一维数组
     * @param int 选填 $operate 操作 1 转为json 2 序列化 ;
     * @param keyPush 键加入无素 1 $pre 键前缀 2 当前控制器方法名;
     * @return mixed ;; false失败
     */
    public static function getCacheData($pre, &$cacheKey, $paramKeyValArr, $operate, $keyPush = 0){
        $dir = __DIR__;// 加入当前文件路径，防止一个服务器布置多个站点时，缓存键相同，被复盖。
        array_push($paramKeyValArr, $dir);

        if( ($keyPush & 1) == 1)  array_push($paramKeyValArr, $pre);

        if( ($keyPush & 2) == 2){
            $actionMethod = self::getActionMethod();// 当前控制器方法名  App\Http\Controllers\weixiu\IndexController@index
            array_push($paramKeyValArr, $actionMethod);
        }
        $temArr = [];
        foreach ( $paramKeyValArr as $k => $v) {
            if(! is_string($v) && ! is_numeric($v)){
                $v = serialize($v);
            }
            array_push($temArr, $k . '$@' . $v);
        }
        $cacheKey = md5(implode("#!%", $temArr));
        return self::getRedis($pre .$cacheKey, $operate);
    }

    /**
     * 保存redis值-json/序列化保存
     * @param string 必填 $pre 前缀
     * @param string $key 键 null 自动生成
     * @param string 选填 $value 需要保存的值，如果是对象或数组，则序列化
     * @param int 选填 $expire 有效期 秒 <=0 长期有效
     * @param int 选填 $operate 操作 1 转为json 2 序列化
     * @return string $key [含前缀]
     * @author zouyan(305463219@qq.com)
     */
    public static function cacheData($pre = '', $key = null, $value = '', $expire = 0, $operate = 1)
    {
        // 缓存数据
        return self::setRedis($pre, $key, $value, $expire , $operate); // 1天
    }

    /**
     * 列出日期區間的 所有日期清單
     * @param string $first 开始日期 YYYY-MM-DD
     * @param string $last 结束日期 YYYY-MM-DD
     * @param string $step 步长 '+1 day'
     * @param string $format 日期格式化 'Y-m-d'
     * @return array $dates  区间内的日期[含]
     * @author zouyan(305463219@qq.com)
     */
    public static function dateRange($first, $last, $step = '+1 day', $format = 'Y-m-d')
    {
        $dates   = [];
        $current = strtotime($first);
        $last    = strtotime($last);

        while ($current <= $last) {
            $dates[] = date($format, $current);
            $current = strtotime($step, $current);
        }
        return $dates;
    }

    /**
     * 列出日期區間的 所有月清單
     * @param string $start 开始日期 YYYY-MM-DD
     * @param string $end 结束日期 YYYY-MM-DD
     * @return array $dates  区间内的月[含] [201809,201810]
     * @author zouyan(305463219@qq.com)
     */
    public static function showMonthRange($start, $end)
    {
        $end = date('Ym', strtotime($end)); // 转换为月
        $range = [];
        $i = 0;
        do {
            $month = date('Ym', strtotime($start . ' + ' . $i . ' month'));
            //echo $i . ':' . $month . '<br>';
            $range[] = $month;
            $i++;
        } while ($month < $end);

        return $range;
    }

    /**
     * 列出日期區間的 所有年清單
     * @param string $start 开始日期 YYYY-MM-DD
     * @param string $end 结束日期 YYYY-MM-DD
     * @return array $dates  区间内的年[含] [2015,2016,2017,2018]
     * @author zouyan(305463219@qq.com)
     */
    public static function showYearRange($start, $end)
    {
        $end = date('Y', strtotime($end)); // 转换为月
        $range = [];
        $i = 0;
        do {
            $year = date('Y', strtotime($start . ' + ' . $i . ' year'));
            //echo $i . ':' . $year . '<br>';
            $range[] = $year;
            $i++;
        } while ($year < $end);

        return $range;
    }

    /**
     * 你上面的方法我觉得不怎么好，介绍一下我写的一个方法。方法函数如下，这样当你要的结果001的话，方法：dispRepair('1',3,'0')
     * 功能：补位函数
     * @param string str 原字符串
     * @param string len 新字符串长度
     * @param string $msg 填补字符
     * @param string $type 类型，0为后补，1为前补
     * @return array $dates  区间内的年[含] [2015,2016,2017,2018]
     * @author zouyan(305463219@qq.com)
     */
    public static function dispRepair($str, $len, $msg, $type = '1') {
        $length = $len - strlen($str);
        if ($length<1) return $str;
        if ($type == 1) {
            $str = str_repeat($msg, $length) . $str;
        } else {
            $str .= str_repeat($msg, $length);
        }
        return $str;
    }

    /**
     * 功能：获得日期
     * @param int $dateType 日期类型 1本周一;2 本周日;3 上周一;4 上周日;5 本月一日;6 本月最后一日;7 上月一日;8 上月最后一日;9 本年一日;10 本年最后一日;11 上年一日;12 上年最后一日
     * @return mixed $date 日期
     * @author zouyan(305463219@qq.com)
     */
    public static function getDateByType($dateType){
        switch($dateType){
            case 1://1本周一;
                return date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600)); //w为星期几的数字形式,这里0为周日
                break;
            case 2://2 本周日;
                return date('Y-m-d', (time() + (7 - (date('w') == 0 ? 7 : date('w'))) * 24 * 3600)); //同样使用w,以现在与周日相关天数算
                break;
            case 3://3 上周一;
                // return date('Y-m-d', strtotime('-1 wednesday', time())); //无论今天几号,-1 monday为上一个有效周未
                return date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600) - 7*24*60*60); //本周一 减七天;
                break;
            case 4:// 4 上周日;
                // return date('Y-m-d', strtotime('-1 sunday', time())); //上一个有效周日,同样适用于其它星期;
                return date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600) - 1*24*60*60); //本周一 减一天;
                break;
            case 5:// 5 本月一日;
                return date('Y-m-d', strtotime(date('Y-m', time()) . '-01 00:00:00')); //直接以strtotime生成;
                break;
            case 6:// 6 本月最后一日;
                return date('Y-m-d', strtotime(date('Y-m', time()) . '-' . date('t', time()) . ' 00:00:00')); //t为当月天数,28至31天
                break;
            case 7:// 7 上月一日;
                return date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m', time()) . '-01 00:00:00'))); //本月一日直接strtotime上减一个月;
                break;
            case 8:// 8 上月最后一日
                return date('Y-m-d', strtotime(date('Y-m', time()) . '-01 00:00:00') - 86400); //本月一日减一天即是上月最后一日;
                break;
            case 9:// 9 本年一日
                return date("Y-01-01");
                break;
            case 10:// 10 本年最后一日
                return date("Y-12-31");
                break;
            case 11:// 11 上年一日
                return date('Y-01-01', strtotime(date('Y-m-d') . ' -1 year'));
                break;
            case 12:// 12 上年最后一日
                return date('Y-12-31', strtotime(date('Y-m-d') . ' -1 year'));
                break;
            default:
                break;
        }
        return '';
    }

    /**
     * 功能：开始、结束日期 判断
     * @param string $begin_date 开始日期
     * @param string $end_date 结束日期
     * @param int $judge_type 1 判断开始日期不能为空 ; 2 判断结束日期不能为空；
     *                        4 开始日期 不能大于 >  当前日；8 开始日期 不能等于 =  当前日；16 开始日期 不能小于 <  当前日
     *                        32 结束日期 不能大于 >  当前日；64 结束日期 不能等于 =  当前日；128 结束日期 不能小于 <  当前日
     *                        256 开始日期 不能大于 >  结束日期；512 开始日期 不能等于 =  结束日期；1024 开始日期 不能小于 <  结束日期
     * @param string $errDo 错误处理方式 1 throws 2直接返回错误
     * @param string $nowTime 比较日期 格式 Y-m-d,默认为当前日期 Y-m-d; 需要时分秒时，可以传 date('Y-m-d H:i:s')
     * @param string $dateName 日期(默认); 时间
     * @return boolean 结果 true通过判断; sting 具体错误 ； throws 错误
     * @author zouyan(305463219@qq.com)
     */
    public static function judgeBeginEndDate($begin_date, $end_date, $judge_type = 0, $errDo = 1, $nowTime = '', $dateName = '日期' ){
//        $begin_date = CommonRequest::get($request, 'begin_date');// 开始日期
//        $end_date = CommonRequest::get($request, 'end_date');// 结束日期
        if(empty($nowTime)) $nowTime = date('Y-m-d');
        $nowTimeUnix = judgeDate($nowTime);

        if( ($judge_type & 1) == 1 && empty($begin_date)){// 1 判断开始日期不能为空
            $errMsg = '开始' . $dateName . '不能为空!';
            if($errDo == 1) throws($errMsg);
            return $errMsg;
        }

        if (!empty($begin_date)) {
            $begin_date_unix = judgeDate($begin_date);
            if($begin_date_unix === false){
                $errMsg = '开始' . $dateName . '不是有效' . $dateName . '!';
                if($errDo == 1) throws($errMsg);
                return $errMsg;
            }
            // 4 开始日期 不能大于 >  当前日
            if(($judge_type & 4) == 4 && $begin_date_unix > $nowTimeUnix ){
                $errMsg = '开始' . $dateName . '不能大于' . $dateName . '[' . $nowTime . ']!';
                if($errDo == 1) throws($errMsg);
                return $errMsg;
            }

            // 8 开始日期 不能等于 =  当前日
            if(($judge_type & 8) == 8 && $begin_date_unix == $nowTimeUnix ){
                $errMsg = '开始' . $dateName . '不能等于' . $dateName . '[' . $nowTime . ']!';
                if($errDo == 1) throws($errMsg);
                return $errMsg;
            }

            // 16 开始日期 不能小于 <  当前日
            if(($judge_type & 16) == 16 && $begin_date_unix < $nowTimeUnix ){
                $errMsg = '开始' . $dateName . '不能小于' . $dateName . '[' . $nowTime . ']!';
                if($errDo == 1) throws($errMsg);
                return $errMsg;
            }
        }

        if( ($judge_type & 2) == 2 && empty($end_date)){//2 判断结束日期不能为空；
            $errMsg = '结束' . $dateName . '不能为空!';
            if($errDo == 1) throws($errMsg);
            return $errMsg;
        }

        if (!empty($end_date)) {
            $end_date_unix = judgeDate($end_date);
            if($end_date_unix === false){
                $errMsg = '结束' . $dateName . '不是有效' . $dateName . '!';
                if($errDo == 1) throws($errMsg);
                return $errMsg;
            }

            // 32 结束日期 不能大于 >  当前日
            if(($judge_type & 32) == 32 && $end_date_unix > $nowTimeUnix ){
                $errMsg = '结束' . $dateName . '不能大于' . $dateName . '[' . $nowTime . ']!';
                if($errDo == 1) throws($errMsg);
                return $errMsg;
            }

            // 64 结束日期 不能等于 =  当前日
            if(($judge_type & 64) == 64 && $end_date_unix == $nowTimeUnix ){
                $errMsg = '结束' . $dateName . '不能等于' . $dateName . '[' . $nowTime . ']!';
                if($errDo == 1) throws($errMsg);
                return $errMsg;
            }

            // 128 结束日期 不能小于 <  当前日
            if(($judge_type & 128) == 128 && $end_date_unix < $nowTimeUnix ){
                $errMsg = '结束' . $dateName . '不能小于' . $dateName . '[' . $nowTime . ']!';
                if($errDo == 1) throws($errMsg);
                return $errMsg;
            }
        }

        if(!empty($begin_date) && !empty($end_date) ){

            // 256 开始日期 不能大于 >  结束日期；
            if(($judge_type & 256) == 256 && $begin_date_unix > $end_date_unix ){
                $errMsg = '开始' . $dateName . '不能大于结束' . $dateName . '!';
                if($errDo == 1) throws($errMsg);
                return $errMsg;
            }

            // 512 开始日期 不能等于 =  结束日期；
            if(($judge_type & 512) == 512 && $begin_date_unix == $end_date_unix ){
                $errMsg = '开始' . $dateName . '不能等于结束' . $dateName . '!';
                if($errDo == 1) throws($errMsg);
                return $errMsg;
            }

            // 1024 开始日期 不能小于 <  结束日期
            if(($judge_type & 1024) == 1024 && $begin_date_unix < $end_date_unix ){
                $errMsg = '开始' . $dateName . '不能小于结束' . $dateName . '!';
                if($errDo == 1) throws($errMsg);
                return $errMsg;
            }
        }
        return true;
    }

    /**
     * 功能：日期 加/减操作
     * @param string $operateDate 操作日期/时间;// 为空，则操作当前时间
     * @param array $oprates 操作类型 一维数组, 下面空格拼接执行
     [
    // +1 day +1 hour +1 minute  可以随便自由组合，以达到任意输出时间的目的
    // -1 day  ---昨天  // 可以修改参数1为任何想需要的数  day也可以改成year（年），month（月），hour（小时），minute（分），second（秒）
    // +1 day  ---明天
    // +1 week  ---一周后
    // +1 week 2 days 4 hours 2 seconds  ---一周零两天四小时两秒后
    // next Thursday   ---下个星期四
    // last Monday  --- 上个周一
    // last month  ---一个月前
    // +1 month  ---一个月后
    // +10 year  ---十年后
     ]
     * @param string $format 返回数据格式化 "Y-m-d H:i:s","Y-m-d","H:i:s"
     * @param string $errDo 错误处理方式 1 throws 2直接返回错误
     * @param string $dateName 日期(默认); 时间
     * @return mixed  sting 具体错误 ； throws 错误
     * @author zouyan(305463219@qq.com)
     */
    public static function addMinusDate($operateDate, $oprates = [], $format = 'Y-m-d H:i:s', $errDo = 1, $dateName = '时间')
    {
        // date_default_timezone_set('PRC'); //默认时区
        if(empty($operateDate)) $operateDate = date('Y-m-d H:i:s');
        // 开始时间
        $date_unix = judgeDate($operateDate);
        if($date_unix === false){
            $errMsg = '开始' . $dateName . '不是有效' . $dateName . '!';
            if($errDo == 1) throws($errMsg);
            return $errMsg;
        }
        // date('Y-m-d', strtotime ("+1 day", strtotime('2011-11-01')))
        if(!empty($oprates)){
            return date($format, strtotime (implode(' ', $oprates), strtotime(judgeDate($date_unix, "Y-m-d H:i:s"))));
        }
        return judgeDate($date_unix, $format);
    }

    /**
     * 功能：开始、结束日期 差--单位秒
     * @param string $begin_date 开始日期
     * @param string $end_date 结束日期,默认当前时间
     * @param string $errDo 错误处理方式 1 throws 2直接返回错误
     * @param string $dateName 日期(默认); 时间
     * @param int $reType  类型 1[默认]只返回正值 ; 2 $end_date - $begin_date ;3 $begin_date - $end_date
     * @return mixed  sting 具体错误 ； throws 错误
     * @author zouyan(305463219@qq.com)
     */
    public static function diffDate($begin_date, $end_date = '', $errDo = 1, $dateName = '时间', $reType = 1){

        if(empty($end_date)) $end_date = date('Y-m-d H:i:s');

        // 开始时间
        $begin_date_unix = judgeDate($begin_date);
        if($begin_date_unix === false){
            $errMsg = '开始' . $dateName . '不是有效' . $dateName . '!';
            if($errDo == 1) throws($errMsg);
            return $errMsg;
        }

        // 结束时间
        $end_date_unix = judgeDate($end_date);
        if($end_date_unix === false){
            $errMsg = '结束' . $dateName . '不是有效' . $dateName . '!';
            if($errDo == 1) throws($errMsg);
            return $errMsg;
        }

        $starttime = $begin_date_unix;
        $endtime = $end_date_unix;
        switch($reType) {
            case 2://  2 $end_date - $begin_date ;
                break;
            case 3:// 3 $begin_date - $end_date
                $starttime = $end_date_unix;
                $endtime = $begin_date_unix;
                break;
            case 1:// 1[默认]只返回正值 ;
            default:
                if($begin_date_unix <= $end_date_unix){
                    $starttime = $begin_date_unix;
                    $endtime = $end_date_unix;
                }else{
                    $starttime = $end_date_unix;
                    $endtime = $begin_date_unix;
                }
                break;
        }
        //计算天数
        $timediff = $endtime - $starttime;

        return $timediff;
    }

    /**
     * 功能：格式化时间差--以年为基准
     * @param int $timediff 时间差秒数
     * @param string $errDo 错误处理方式 1 throws 2直接返回错误
     * @param string $dateName 日期(默认); 时间
     * @return mixed  sting 具体错误 ； throws 错误
     * @author zouyan(305463219@qq.com)
     */
    public static function formatTimeDiff($timediff){
        $timediff = abs($timediff);
        // 1: 总年 ; 2 总年[向上取整] ;
        // 8 总天数 ; 16 总天数[向上取整] ;32 天数[去除年] 64 天数[去除年][向上取整]
        // 128 总时数 ; 256 总时数[向上取整] ;512 时数[去除年天] 1024 时数[去除年天][向上取整]
        // 2048 总分数 ; 4096 总分数[向上取整] ;8192 分数[去除年天时] 16384 分数[去除年天时][向上取整]
        // 总秒数  秒数[去除年天时分]
        // 返回类型

        // 多少年
        // 总年 ;
        $yearInt = intval($timediff / (365 * 24 * 60 * 60) );
        //  总年[向上取整] ;
        $yearCeil = ceil($timediff / (365 * 24 * 60 * 60) );

        // 8 总天数 ; 16 总天数[向上取整] ;32 天数[去除年] 64 天数[去除年][向上取整]
        $daysInt = intval($timediff / (24 * 60 * 60) ); // 86400  多少天
        $daysCeil = ceil($timediff / (24 * 60 * 60) ); // 86400  多少天

        // 去除年的天数
        $yearRemain = $timediff % (365 * 24 * 60 * 60);
        $daysRemainInt = intval($yearRemain / (24 * 60 * 60) ); // 86400  多少天
        $daysRemainCeil = ceil($yearRemain / (24 * 60 * 60) ); // 86400  多少天

        // 128 总时数 ; 256 总时数[向上取整] ;512 时数[去除年天] 1024 时数[去除年天][向上取整]
        //计算小时数
        $hoursInt = intval($timediff / (60 * 60));// 多少小时 3600
        $hoursCeil = ceil($timediff / (60 * 60));// 多少小时 3600

        // 去除年天
        $remain = $timediff % (24 * 60 * 60);// 86400
        $hoursRemain = intval($remain / (60 * 60));// 多少小时 3600
        $hoursRemainCeil = ceil($remain / (60 * 60));// 多少小时 3600

        // 2048 总分数 ; 4096 总分数[向上取整] ;8192 分数[去除年天时] 16384 分数[去除年天时][向上取整]
        //计算分钟数
        $minsInt = intval($timediff / 60); // 多少分钟
        $minsCeil = ceil($timediff / 60); // 多少分钟

        $remain = $remain % (60 * 60);// 3600
        $minsRemain = intval($remain / 60); // 多少分钟
        $minsRemainCeil = ceil($remain / 60); // 多少分钟

        //计算秒数
        $secsRemain = $remain % 60; // 多少秒
        $secsRemainCeil = ceil($remain % 60); // 多少秒
        return [
            "yearInt" => $yearInt // 总年
            ,"yearCeil" => $yearCeil // 总年[向上取整]

            ,"daysInt" => $daysInt // 总天数
            ,"daysCeil" => $daysCeil // 总天数[向上取整]
            ,"daysRemainInt" => $daysRemainInt // 天数[去除年]
            ,"daysRemainCeil" => $daysRemainCeil // 天数[去除年][向上取整]

            ,"hoursInt" => $hoursInt // 总时数
            ,"hoursCeil" => $hoursCeil // 总时数[向上取整]
            ,"hoursRemain" => $hoursRemain // 时数[去除年天]
            ,"hoursRemainCeil" => $hoursRemainCeil // 时数[去除年天][向上取整]

            ,"minsInt" => $minsInt // 总分数
            ,"minsCeil" => $minsCeil // 总分数[向上取整]
            ,"minsRemain" => $minsRemain // 分数[去除年天时]
            ,"minsRemainCeil" => $minsRemainCeil // 分数[去除年天时][向上取整]

            ,"timediff" => $timediff // 总秒数
            ,"minsRemain" => $minsRemain // 秒数[去除年天时分]
            ,"secsRemainCeil" => $secsRemainCeil // 秒数[去除年天时分][向上取整]
            // ,"aaaa" => $aaaaa // aaaaa
        ];
    }

    /**
     * 功能：计算两个时间内相差多少个月
     *
     * @param string $start_m 开始日期 --小
     * @param string $end_m 结束日期,默认当前时间  --大
     * @param string $errDo 错误处理方式 1 throws 2直接返回错误
     * @param string $dateName 日期(默认); 时间
     * @return mixed  sting 具体错误 ； throws 错误
     * @author zouyan(305463219@qq.com)
     *
    // 总月数
    $monthInt = Tool::diffMonth($starttime, $endtime, $errDo, $dateName);
    // 月数[去除年月]
    $monthRemainInt = $monthInt % 12;
     */
    public static function diffMonth($begin_date, $end_date, $errDo = 1, $dateName = '时间'){

        // 开始时间
        $begin_date_unix = judgeDate($begin_date);
        if($begin_date_unix === false){
            $errMsg = '开始' . $dateName . '不是有效' . $dateName . '!';
            if($errDo == 1) throws($errMsg);
            return $errMsg;
        }

        // 结束时间
        $end_date_unix = judgeDate($end_date);
        if($end_date_unix === false){
            $errMsg = '结束' . $dateName . '不是有效' . $dateName . '!';
            if($errDo == 1) throws($errMsg);
            return $errMsg;
        }

        if($begin_date_unix <= $end_date_unix){
            $starttime = $begin_date_unix;
            $endtime = $end_date_unix;
        }else{
            $starttime = $end_date_unix;
            $endtime = $begin_date_unix;
        }

        $starttime = judgeDate($starttime, "Y-m-d H:i:s");
        $endtime = judgeDate($endtime, "Y-m-d H:i:s");

        $date1 = explode('-',$starttime);
        $date2 = explode('-',$endtime);

        if($date1[1] < $date2[1]){ //判断月份大小，进行相应加或减
            $month_number = abs($date1[0] - $date2[0]) * 12 + abs($date1[1] - $date2[1]);
        }else{
            $month_number = abs($date1[0] - $date2[0]) * 12 - abs($date1[1] - $date2[1]);
        }
        return $month_number;
    }

    /**
     * 功能：验证数据
     * @param array $valiDateParam 需要验证的条件
    $valiDateParam= [
    //["input"=>$_POST["title"],"require"=>"true","message"=>'闪购名称不能为空'],  -- 必填  -- require是否必填，可以与下面的一方一起参与验证
    ["input"=>$_POST["state"],"require"=>"false","validator"=>"custom","regexp"=>"/^([01]|10)$/","message"=>'闪购状态值有误'],--正则
    ["input"=>$_POST["title"],"require"=>"false","validator"=>"length","min"=>"1","max"=>"160","message"=>'闪购名称长度为1~ 160个字符'],--判断长度
    ["input"=>$_POST["title"],"require"=>"false","validator"=>"compare","operator"=>"比较符>=<=","to"=>"被比较值","message"=>'闪购名称不能大于10'],--比较
    ["input"=>$_POST["title"],"require"=>"false","validator"=>"range","min"=>"最小值1","max"=>"最大值10","message"=>'闪购值必须大于等于1且小于等于10'],--范围
    ["input"=>$_POST["market_id"],"require"=>"false","validator"=>"integer","message"=>'闪购地编号必须为数值'], --配置好的
    ];
     * @param string $errDo 错误处理方式 1 throws 2直接返回错误
     * @return string  错误信息 ，没有错，则为空
     * @author zouyan(305463219@qq.com)
     */
    public static function dataValid($valiDateParam = [], $errDo = 1) {
        if(empty($valiDateParam) || (!is_array($valiDateParam))){
            return false;
        }
        $validateObj = new Validate();
        $validateObj->validateparam = $valiDateParam;
        // return $validateObj->validate();
        $error = $validateObj->validate();
        if ($error != ''){
            if($errDo == 1) throws($error);
            return $error;
            // output_error($error);
        }
        return '';
    }

    /**
     * 功能：获得框文件夹绝对路径
     * @param string $pathKey 路径关键字
     *       app   app目录的绝对路径 srv/www/work/work.0101jz.com/app
     *       base  项目根目录的绝对路径 /srv/www/work/work.0101jz.com
     *       base   'public'  相对于应用目录的给定文件生成绝对路径 /srv/www/work/work.0101jz.com/public
     *       config 应用配置目录的绝对路径  /srv/www/work/work.0101jz.com/config
     *       database 应用数据库目录的绝对路径 /srv/www/work/work.0101jz.com/database
     *       public public目录的绝对路径 /srv/www/work/work.0101jz.com/public
     *       storage   storage目录的绝对路径 /srv/www/work/work.0101jz.com/storage
     *       storage    'app/file.txt'   还可以使用storage_path函数生成相对于storage目录的给定文件的绝对路径 /srv/www/work/work.0101jz.com/storage/app/file.txt
     * @param string $dir 目录或文件
     * @return string  绝对路径
     * @author zouyan(305463219@qq.com)
     */
    public static function getPath($pathKey = '', $dir = ''){
        $returnPath = '';
        switch (strtolower($pathKey)) {
            case 'app':
                // app_path();//app目录的绝对路径 srv/www/work/work.0101jz.com/app
                $returnPath = app_path();
                break;
            case 'base':
                // base_path();// 项目根目录的绝对路径 /srv/www/work/work.0101jz.com
                // $path = base_path('vendor/bin'); // 相对于应用目录的给定文件生成绝对路径
                //    base_path('public') ;// /srv/www/work/work.0101jz.com/public
                if(empty($dir)){
                    $returnPath = base_path();
                }else{
                    $returnPath = base_path($dir);
                }
                break;
            case 'config':
                // config_path();  // 应用配置目录的绝对路径  /srv/www/work/work.0101jz.com/config
                $returnPath = config_path();
                break;
            case 'database':
                // database_path();// 应用数据库目录的绝对路径 /srv/www/work/work.0101jz.com/database
                $returnPath = database_path();
                break;
            case 'public':
                // public_path(); // public目录的绝对路径 /srv/www/work/work.0101jz.com/public
                $returnPath = public_path();
                break;
            case 'storage':
                // storage_path(); // storage目录的绝对路径 /srv/www/work/work.0101jz.com/storage
                // storage_path('app/file.txt')还可以使用storage_path函数生成相对于storage目录的给定文件的绝对路径 /srv/www/work/work.0101jz.com/storage/app/file.txt
                if(empty($dir)){
                    $returnPath = storage_path();
                }else{
                    $returnPath = storage_path($dir);
                }
                break;
            default:
                break;
        }
        return $returnPath;
    }

    /**
     * 功能：对二维数组,按指定多个下标进行排序
     * @param array $data 需要排序的二维数组[如数据表数据-二维数据]
     * @param array  $keys ,用来排序的字段
     *      key:字段下标 ;
     *      sort:排序顺序标志;asc[按照上升顺序排序]-默认,desc[按照下降顺序排序]；
     *      type: 排序类型标志;regular[将项目按照通常方法比较]-默认,numeric[将项目按照数值比较],string[将项目按照字符串比较]
     *      array(
     *          array(key=>col1, sort=>desc),
     *          array(key=>col2, type=>numeric)
     *      )
     * @return array 排序后的二维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function php_multisort($data, $keys){
        if(empty($data) || (!is_array($data)))  return $data;
        // List As Columns
        foreach ($data as $key => $row) {
            foreach ($keys as $k){
                $cols[$k['key']][$key] = $row[$k['key']];
            }
        }
        // List original keys
        $idkeys=array_keys($data);
        // Sort Expression
        $i=0;
        $sort = '';
        foreach ($keys as $k){
            if($i>0){$sort.=',';}
            $sort.='$cols["'.$k['key'].'"]';
            if($k['sort']){$sort.=',SORT_'.strtoupper($k['sort']);}
            if($k['type']){$sort.=',SORT_'.strtoupper($k['type']);}
            $i++;
        }
        $sort.=',$idkeys';
        // Sort Funct
        $sort='array_multisort('.$sort.');';
        eval($sort);
        // Rebuild Full Array
        foreach($idkeys as $idkey){
            $result[$idkey]=$data[$idkey];
        }
        return $result;
    }


    // 判断参数
    public static function judgeInitParams($paramName, $pramVal)
    {
        if (((int )$pramVal) <=0){
            throws('参数[' . $paramName . ']必须为整数！');
        }
    }

    // 判断是否为空
    public static function judgeEmptyParams($paramName, $pramVal)
    {
        if (empty($pramVal)){
            throws('参数[' . $paramName . ']不能为空！');
        }
    }


    // 后缀可区分环境
    public static function getSnSuffix()
    {
        static $suffixes = [
            'dev'  => 1,
            'test' => 2,
            'prod' => 0,
        ];

        $suffix = $suffixes[YII_ENV] ?? 9;

        return $suffix;
    }

    /**
     * 获得属性
     *
     * @param object $modelObj 对象
     * @param string $attrName 属性名称[静态或动态]--注意静态时不要加$ ,与动态属性一样 如 attrTest
     * @param int $isStatic 是否静态属性 0：动态属性 1 静态属性
     * @return string 属性值
     * @author zouyan(305463219@qq.com)
     */
    public static function getAttr(&$modelObj, $attrName, $isStatic = 0){
        if ( !property_exists($modelObj, $attrName)) {
            throws("未定义[" . $attrName  . "] 属性");
        }
        // 静态
        if($isStatic == 1) return $modelObj::${$attrName};
        return $modelObj->{$attrName};
    }

    /**
     * 调用模型方法
     *  模型中方法定义:注意参数尽可能给默认值
        public function aaa($aa = [], $bb = []){
            echo $this->getTable() . '<BR/>';
            print_r($aa);
            echo  '<BR/>';
            print_r($bb);
            echo  '<BR/>';
            echo 'aaaaafunction';
        }
     * @param object $modelObj 对象
     * @param string $methodName 方法名称
     * @param array $params 参数数组 没有参数:[]  或 [第一个参数, 第二个参数,....];
     * @return mixed 返回值
     * @author zouyan(305463219@qq.com)
     */
    public static function exeMethod(&$modelObj, $methodName, $params = []){
        if(!method_exists($modelObj,$methodName)){
            throws("未定义[" . $methodName  . "] 方法");
        }
        $params = array_values($params);
        return $modelObj->{$methodName}(...$params);
    }

    /**
     * 根据数据表记录[二维]，转换资源url为可以访问的地址
     *
     * @param array $reportsList 栏目记录数组 - 二维
     * @param int $type 多少维  1:一维[默认]；2 二维 --注意是资源的维度
     * @author zouyan(305463219@qq.com)
     */
    public static function resoursceUrl(&$reportsList, $type = 2){
        foreach($reportsList as $k=>$item){
            $reportsList[$k] = static::resourceUrl($item,$type);
        }
        return $reportsList;
    }

    /**
     * 根据数据表记录，转换资源url为可以访问的地址
     *
     * @param array $dataList 资源记录数组 - 二维 / 一维
     * @param int $type 多少维  1:一维[默认]；2 二维 --注意是资源的维度
     * @author zouyan(305463219@qq.com)
     */
    public static function resourceUrl(&$dataList,$type = 2){
        if($type == 2){
            if(isset($dataList['site_resources'])){
                $site_resources = $dataList['site_resources'] ?? [];
                foreach($site_resources as $k=>$site_resource){
                    $site_resources[$k]['resource_url'] = url($site_resource['resource_url']);
                }
                $dataList['site_resources'] = $site_resources;
            }
        }else{
            if(isset($dataList['resource_url'])){
                $dataList['resource_url'] = url($dataList['resource_url']);
            }
        }
        return $dataList;
    }

    /**
     * 格式化资源数据
     *
     * @param array $dataList 资源记录数组 - 二维 / 一维
     * @param int $type 多少维  1:一维[默认]；2 二维 --注意是资源的维度
     * @author zouyan(305463219@qq.com)
     */
    public static function formatResource($data_list, $type = 2){
        $reList = [];
        if($type == 1) $data_list = [$data_list];
        foreach($data_list as $k => $v){
            $temArr = [
                'id' => $v['id'],
                'resource_name' => $v['resource_name'],
                'resource_url' => url($v['resource_url']),
                'created_at' => $v['created_at'],
            ];
            array_push($reList, $temArr);
        }
        if($type == 1) $reList = $reList[0] ?? [];
        return $reList;
    }

    /**
     * 根据数据表记录，删除本地文件
     *
     * @param object $modelObj 当前模型对象
     * @param array $resources 资源记录数组 - 二维
     * @author zouyan(305463219@qq.com)
     */
    public static function resourceDelFile($resources = []){
        foreach($resources as $resource){
            $resource_url = $resource['resource_url'] ?? '';
            if(empty($resource_url)){
                continue;
            }
            @unlink(public_path($resource_url));// 删除文件
        }
    }

    /**
     * 根据site_resources记录，转换小程序的图片列数组-二维
     *
     * @param array $site_resources 资源记录数组 - 二维
     * @return  array $upload_picture_list 小程序的图片列数组-二维
     * @author zouyan(305463219@qq.com)
     */
    public static function getFormatResource($site_resources){
        $upload_picture_list = [];
        // $site_resources = $infoData['site_resources'] ?? [];
        foreach($site_resources as $v){
            $upload_picture_list[] = [
                'upload_percent' => 100,
                'path' => $v['resource_url'] ?? '',
                'path_server' => $v['resource_url'] ?? '',
                'resource_id' => $v['id'] ?? 0,
            ];
        }
        //$infoData['upload_picture_list'] = $upload_picture_list;
        return $upload_picture_list;
    }

    /**
     * 格式化字符串--字符串每隔多少个字符加指定字符
     *
     * @param string $str 字符串
     * @param string $splitStr 指定字符--默认空隔
     * @param int  $len 每隔多少长度--默认4
     * @return  string 格式化后字符串
     * @author zouyan(305463219@qq.com)
     */
    public static function formatStrMiddle($str, $splitStr = ' ', $len = 4){
        return implode($splitStr, str_split($str, $len));
    }

    /**
     * 格式化后手机/电话号码
     *
     * @param string $str 需要格式化的字符
    $formatArr = [
    [
    'len' => 3,// 长度
    'splitStr' => '',// 分隔符
    ],
    ....
    ];
     * @return  string 格式化后手机/电话号码
     * @author zouyan(305463219@qq.com)
     */
    public static function formatStr($str, $formatArr = []) {
        $reStr = '';
        $strLen = strlen($str);
        foreach($formatArr as $v){
            $len = $v['len'] ?? 1;
            if($len < 1) $len = 1;
            $splitStr = $v['splitStr'] ?? ' ';
            if($splitStr == '') $splitStr = ' ';
            $reStr .= substr($str,0, $len);

            // 剩下的字符
            $str = substr($str,$len);
            $strLen = strlen($str);
            if($strLen > 0) $reStr .= $splitStr;
        }
        // 加上剩下的
        if($strLen > 0) $reStr .= $str;

        return $reStr;
        // $phone = preg_replace("/[^0-9]/", "", $phone);
        // $replacement = [];// 用于替换的字符串或字符串数组。
        // return preg_replace("/([0-9]{3})([0-9]{4})([0-9]{4})/","$1 $2 $3",$phone);
        /*
        if(strlen($phone) == 7)// 029-88214602  0831-6746036
            return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
        elseif(strlen($phone) == 10)
            return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/","($1) $2-$3",$phone);
        elseif(strlen($phone) == 11)
            return preg_replace("/([0-9]{3})([0-9]{4})([0-9]{4})/","$1 $2 $3",$phone);
        else
            return $phone;
        */
    }

    /**
     *  关键数据生成键 ,
     *
     * @param array $keyData 其内容项可以是字符、数字、数组
     * @param string $reType 返回的值类型 md5
     * @return  string md5值
     * @author zouyan(305463219@qq.com)
     */
    public static function getUniqueKey($keyData, $reType = 'md5') {
        $keyArr = [];
        foreach($keyData as $k => $v){
            array_push($keyArr, $k);
            if(is_numeric($v) || is_string($v)) array_push($keyArr, $v);
            if(is_array($v)) array_push($keyArr, json_encode($v));
        }
        $keyStr = implode('>!@#', $keyArr);
        if($reType == 'md5' ) return md5($keyStr);
        return $keyStr;
    }

    /**
     * 格式化数字保留多少位小数 如:1234.15  3向下取[正负:往小的数取];4 向上取[正负:往大的数取];
     *
     * @param int/float $num 整数或小数
     * @param int $decimalDigits 保留小数位数
     * @param int $type 类型 1 四舍五入;2不四舍五入;3向下取[正负:往小的数取];4 向上取[正负:往大的数取];
     * @param float $sign
     * @return string
     */
    public static function formatFloat($num, $decimalDigits = 2, $type = 2){
        // 判断是否有小数点
        $decNum = 0;// 小数点位数
        if(strpos($num, '.') !== false){ // 没有小数点
            $decNum = strlen($num) - (strpos($num, '.') + 1);// 小数点位数
        }
        switch ($type)
        {
            case 1:// // 保留两位小数并且四舍五入
                // $num = 123213.666666;
                // sprintf("%.2f", $num);
                return sprintf("%." . $decimalDigits . "f", $num);
                break;
            case 2:// 保留两位小数并且不四舍五入
            case 3:// 向下取
                // $num = 123213.666666;
                // echo sprintf("%.2f",substr(sprintf("%.3f", $num), 0, -1));
                if( $decimalDigits <  $decNum){
                    for($i = 1; $i <= $decimalDigits; $i++){
                        $num *= 10;
                    }
                    $numArr = explode('.', $num);
                    if(count($numArr) == 2){
                        $intNum = $numArr[0] ?? 0;
                        $digitNum = $numArr[1] ?? 0;

                        // 向下取整
                        // $num = floor($num);
                        $num = $intNum;
                    }

                    for($i = 1; $i <= $decimalDigits; $i++){
                        $num /= 10;
                    }
                }
                return sprintf("%." . $decimalDigits . "f", $num);
                // return sprintf("%." . $decimalDigits . "f",substr(sprintf("%." . ($decimalDigits + 1) . "f", $num), 0, -1));
                break;
            case 4:// 4 向上取
                if( $decimalDigits <  $decNum){
                    for($i = 1; $i <= $decimalDigits; $i++){
                        $num *= 10;
                    }
                    $numArr = explode('.', $num);
                    if(count($numArr) == 2){
                        $intNum = $numArr[0] ?? 0;
                        $digitNum = $numArr[1] ?? 0;

                        // 向上取整
                        // $num = ceil($num);
                        $num = $intNum;
                        if( $digitNum > 0 ) $num += 1;
                    }

                    for($i = 1; $i <= $decimalDigits; $i++){
                        $num /= 10;
                    }
                }
                return sprintf("%." . $decimalDigits . "f", $num);
            default:
        }
        return $num;
    }


    /**
     * 格式化数字保留多少位小数 如:1234.15  3向下取[正:往小的数取;负:往大的负数取];4 向上取[正:往大的数取;负:往小的数负取];
     *
     * @param int/float $num 整数或小数
     * @param int $decimalDigits 保留小数位数
     * @param int $type 类型 1 四舍五入;2不四舍五入;3向下取[正:往小的数取;负:往大的负数取];4 向上取[正:往大的数取;负:往小的数负取];
     * @param float $sign
     * @return string
     */
    public static function formatFloatVal($num, $decimalDigits = 2, $type = 2){
        // 判断是否有小数点
        $decNum = 0;// 小数点位数
        if(strpos($num, '.') !== false){ // 没有小数点
            $decNum = strlen($num) - (strpos($num, '.') + 1);// 小数点位数
        }
        switch ($type)
        {
            case 1:// // 保留两位小数并且四舍五入
                return static::formatFloat($num, $decimalDigits, 1);
                break;
            case 2:// 保留两位小数并且不四舍五入
                return static::formatFloat($num, $decimalDigits, 2);
                break;
            case 3:// 向下取
                // $num = 123213.666666;
                // echo sprintf("%.2f",substr(sprintf("%.3f", $num), 0, -1));
                if( $decimalDigits <  $decNum){
                    for($i = 1; $i <= $decimalDigits; $i++){
                        $num *= 10;
                    }

                    // 向下取整
                    $num = floor($num);

                    for($i = 1; $i <= $decimalDigits; $i++){
                        $num /= 10;
                    }
                }
                return sprintf("%." . $decimalDigits . "f", $num);
                break;
            case 4:// 4 向上取
                if( $decimalDigits <  $decNum){
                    for($i = 1; $i <= $decimalDigits; $i++){
                        $num *= 10;
                    }
                    // 向上取整
                    $num = ceil($num);
                    for($i = 1; $i <= $decimalDigits; $i++){
                        $num /= 10;
                    }
                }
                return sprintf("%." . $decimalDigits . "f", $num);
            default:
        }
        return $num;
    }

    /**
     * 格式化金额-仅显示用 如:￥1,234.15
     *
     * @param int $money
     * @param int $len
     * @param string $sign
     * @return string
     */
    public static function formatMoney($money, $len=2, $sign='￥'){
        $negative = $money >= 0 ? '' : '-';
        $int_money = intval(abs($money));
        $len = intval(abs($len));
        $decimal = '';//小数
        if ($len > 0) {
            $decimal = '.'.substr(sprintf('%01.'.$len.'f', $money),-$len);
        }
        $tmp_money = strrev($int_money);
        $strlen = strlen($tmp_money);
        $format_money = '';
        for ($i = 3; $i < $strlen; $i += 3) {
            $format_money .= substr($tmp_money,0,3).',';
            $tmp_money = substr($tmp_money,3);
        }
        $format_money .= $tmp_money;
        $format_money = strrev($format_money);
        return $sign.$negative.$format_money.$decimal;
    }

    /**
     *
     * 时间比较
     * @details
     * @param $beginTime 开始时间 05:00:00
     * @param $endTime 结束时间 15:00:00
     * @return boolean  true:结束时间 >= 开始时间 或 false:结束时间 >= 开始时间
     *
     */
    public static function timeDomparison($beginTime, $endTime){
        $beginDate = date('Y-m-d') . ' ' . $beginTime;
        $endDate = date('Y-m-d') . ' ' . $endTime;
        $diffNum = Tool::diffDate($beginDate, $endDate, 1, '时间', 2);
        return $diffNum >= 0 ? true : false;
    }

    // 参数如下方法 timesJudge
    public static function timesJudgeDo($timeList, $judgeRangeTime = '', $judgeType = 0, $errDo = 1, $beginTimeKey = 'begin_time', $endTimeKey = 'end_time', $beginTimeName = '开始时间', $endTimeName = '结束时间', $judgeRangeTimeName = '', $level = 1){
        // 先执行条件数据验证
        $temJudgeType = $judgeType & (1 + 2 + 4);
        if($temJudgeType > 0){
            $result = Tool::timesJudge($timeList, $judgeRangeTime, $temJudgeType, $errDo, $beginTimeKey, $endTimeKey, $beginTimeName, $endTimeName, $judgeRangeTimeName, $level);
            if (is_string($result)) {
                return $result;
            }
        }
        // 再执行时间段数据验证
        $temJudgeType = $judgeType & (8 + 16 + 32 + 64 + 128 + 256);
        if($temJudgeType > 0){
            $result = Tool::timesJudge($timeList, $judgeRangeTime, $temJudgeType, $errDo, $beginTimeKey, $endTimeKey, $beginTimeName, $endTimeName, $judgeRangeTimeName, $level);
            if (is_string($result)) {
                return $result;
            }
        }
        return true;
    }

    /**
     *
     * 多时间段验证 ;具体使用，请用方法 timesJudgeDo
     * @details
     * @param array $timeList 需要验证的时间列表 一维或二维数组 ['begin_time' => '05:00:00', 'end_time'=> '15:00:00']
     * @param string $judgeRangeTime 需要验证范围的时间, 为空：则不做范围验证
     * @param int $judgeType 判断类型  [满足就是错误]
     *                           1 开始时间 < 结束时间 ; 2 开始时间 = 结束时间 ; 4开始时间 > 结束时间
     *
     *                           8 开始时间不能在其它的范围内[不可含任一端] -----需要验证范围的时间
     *                           16 开始时间不能在其它的范围内[不可含左端]  -----需要验证范围的时间
     *                           32 开始时间不能在其它的范围内[不可含右端] -----需要验证范围的时间
     *
     *                          64 结束时间不能在其它的范围内[不可含任一端]  -----需要验证范围的时间
     *                          128 结束时间不能在其它的范围内[不可含左端]  -----需要验证范围的时间
     *                          256 结束时间不能在其它的范围内[不可含右端]   -----需要验证范围的时间
     * @param int $errDo 错误处理方式 1 throws 2直接返回错误
     * @param string $beginTimeKey 开始时间下标
     * @param string $endTimeKey 结束时间下标
     * @param string $beginTimeName 开始时间名称
     * @param string $endTimeName 结束时间名称
     * @param string $judgeRangeTimeName 需要验证范围的时间名称
     * @param string $level 层数 1 :初始调用 2 :第二次调用;最多2层 ；主要作用是不要递卡尔集递归
     * @return mixed  true:成功; string:具体错误
     *
     */
    public static function timesJudge($timeList, $judgeRangeTime = '', $judgeType = 0, $errDo = 1, $beginTimeKey = 'begin_time', $endTimeKey = 'end_time', $beginTimeName = '开始时间', $endTimeName = '结束时间', $judgeRangeTimeName = '', $level = 1){
        if($level > 2) return true;
        // 如果是一维，则变为二维
        if(isset($timeList[$beginTimeKey]) && isset($timeList[$endTimeKey]))  $timeList = [$timeList];
        $timeList = array_values($timeList);
        foreach($timeList as $k => $v){
            $beginTime = $v[$beginTimeKey] ?? '';
            $endTime = $v[$endTimeKey] ?? '';
            // 判断
            if(($judgeType & (1 + 2 + 4 ) ) > 0) {
                $result = compare_time($beginTime, $endTime, $beginTimeName, $endTimeName, $errDo);
                if (is_string($result) && !is_numeric($result)) return $result;// 有错误
                // 1 开始时间 < 结束时间
                if (($judgeType & 1) == 1 && $result > 0) {
                    $errMsg = $beginTimeName . "[" . $beginTime . "]不能小于" . $endTimeName . "[" . $endTime . "]";
                    if ($errDo == 1) throws($errMsg);
                    return $errMsg;
                }
                // 2 开始时间 = 结束时间
                if (($judgeType & 2) == 2 && $result == 0) {
                    $errMsg = $beginTimeName . "[" . $beginTime . "]不能等于" . $endTimeName . "[" . $endTime . "]";
                    if ($errDo == 1) throws($errMsg);
                    return $errMsg;
                }
                // 4开始时间 > 结束时间
                if (($judgeType & 4) == 4 && $result < 0) {
                    $errMsg = $beginTimeName . "[" . $beginTime . "]不能大于" . $endTimeName . "[" . $endTime . "]";
                    if ($errDo == 1) throws($errMsg);
                    return $errMsg;
                }
            }

            // 8 开始时间不能在其它的范围内 -----需要验证范围的时间
            // 16 结束时间不能在其它的范围内 -----需要验证范围的时间
            if( ($judgeType & (8 + 16 + 32 + 64 + 128 + 256) ) > 0  && !empty($judgeRangeTime)  ){
                // 开始时间-判断时间
                $beginRangeDiff = compare_time($judgeRangeTime, $beginTime, $judgeRangeTimeName, $beginTimeName, $errDo);
                if(is_string($beginRangeDiff) && !is_numeric($beginRangeDiff)) return $beginRangeDiff;// 有错误
                // 结束时间-判断时间
                $endRangeDiff = compare_time($judgeRangeTime, $endTime, $judgeRangeTimeName, $endTimeName, $errDo);
                if(is_string($endRangeDiff) && !is_numeric($endRangeDiff)) return $endRangeDiff;// 有错误
                if( ( ($judgeType & (8 + 64) ) > 0 &&  $beginRangeDiff <= 0 && $endRangeDiff >= 0 )
                    ||  ( ($judgeType & (16 + 128) ) > 0 &&  $beginRangeDiff <= 0 && $endRangeDiff > 0 )
                    ||  ( ($judgeType & (32 + 256) ) > 0 &&  $beginRangeDiff < 0 && $endRangeDiff >= 0 )
                ){
                    $errMsg = $judgeRangeTimeName . "[" . $judgeRangeTime . "]不能在时间范围[" . $beginTime . " - " . $endTime . "]";
                    if($errDo == 1) throws($errMsg);
                    return $errMsg;
                }
            }
            // if(empty($judgeRangeTime)) continue;
            if(($judgeType & (8 + 16 + 32 + 64 + 128 + 256) ) <= 0 ) continue;
            if($level >= 2) continue;
            $temOpenTimeList = $timeList;
            for($n = 0; $n <= $k; $n++ ){
                unset($temOpenTimeList[$n]);
            }
            if(empty($temOpenTimeList)) continue;
            // 比较开始时间是否在时间范围
            if(($judgeType & (8 + 16 + 32 ) ) > 0){
                $rangeBegin = Tool::timesJudge($temOpenTimeList, $beginTime, ($judgeType & (8 + 16 + 32 ) ) , $errDo
                    , $beginTimeKey, $endTimeKey, $beginTimeName, $endTimeName, $beginTimeName, 2);
                if(is_string($rangeBegin)){
                    return $rangeBegin;
                }
            }
            // 比较结束时间是否在时间范围
            if(($judgeType & (64 + 128 + 256 ) ) > 0) {
                $rangeEnd = Tool::timesJudge($temOpenTimeList, $endTime, ($judgeType & (64 + 128 + 256)), $errDo
                    , $beginTimeKey, $endTimeKey, $beginTimeName, $endTimeName, $endTimeName, 2);
                if (is_string($rangeEnd)) {
                    return $rangeEnd;
                }
            }
        }
        return true;
    }
}

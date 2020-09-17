<?php
// session方案
// 如果系统有配置  config('public.redis.default.host') 的Redis , 则使用Redis保存 session
// 否则使用 php.ini配置的方式，一般为文件方式保存，优化的话，可以多层文件保存
namespace App\Services\SessionCustom;

/*
    demo.php

    session_start();

    $data = '123456';
    SessionCustom::set('test', $data, 10);
    echo SessionCustom::get('test'); // 未过期，输出
    sleep(10);
    echo SessionCustom::get('test'); // 已过期
*/

use App\Services\Tool;
use Carbon\Carbon;

class SessionCustom{
   // session_save_path('E:/ttt');
    public static $redisHost = 'localhost';
    public static $redisPort = 6379;
    public static $redisAuth = '';// 密码
    public static $redisDatabase = 0;// 缓存数据的Redis 数据库编号
    // Session数据在服务器端储存的时间，如果超过这个时间，那么Session数据就自动删除！
    // 一晚上没关电脑浏览器，第二天上午还能正常继期-----所以继期时间
    // 要大于  下午5点下班（24-17） + 第二天上午全上午（ 12-0） =  7 + 12 = 19  ,
    //  那就登录状态缓存有效期设为  20小时吧
    public static $gcMaxlifetime = 60 * 60 * 20;


    /**
     * 获得Session数据在服务器端储存的时间，如果超过这个时间，那么Session数据就自动删除！
     */
    public static function getGcMaxlifetime(){
        $gcMaxlifetime = config('public.sessionGCMaxlifetime', static::$gcMaxlifetime);
        if( !is_numeric($gcMaxlifetime)) $gcMaxlifetime = static::$gcMaxlifetime;
        return $gcMaxlifetime;
    }

    /**
     * 初始化session
     * $redisConfig = [
     *   'host' => 'localhost',// 主机 ：无下标【使用属性指定的】
     *   'port' => 6379,// 端口 ：无下标【使用属性指定的】
     *   'auth' => '',// 密码：无下标【使用属性指定的】
     *   'database' => 2,//  无下标：-1【使用属性指定的】； （整数）：选择不同的数据库。 < 0 时：使用配置，配置没有再使用默认指定的
     * ];
     */
    public static function sesseionStart($redisConfig = []){
        // 已经开启过会话，则直接返回
        if (session_id()) return ;
        $redisHost = $redisConfig['host'] ?? '';
        $redisPort = $redisConfig['port'] ?? '';
        $redisAuth = $redisConfig['auth'] ?? '';
        $redisDatabase = $redisConfig['database'] ?? -1;

        if(empty($redisHost)) $redisHost = config('public.redis.default.host', static::$redisHost);// 'localhost'); // '127.0.0.1';//
        if(empty($redisPort)) $redisPort = config('public.redis.default.port', static::$redisPort);// 6379);
        // php session 存储到redis里(转)
        if( strlen($redisHost) > 0 && strlen($redisPort) > 0){
            if(empty($redisAuth)) $redisAuth =  config('public.redis.default.password', static::$redisAuth);// '');
            if(!is_numeric($redisDatabase) || $redisDatabase < 0){
                $redisDatabase =  config('public.sessionRedisDatabase', static::$redisDatabase);
            }

            $savePath = 'tcp://' . $redisHost . ':' . $redisPort;// "tcp://127.0.0.1:6379"
            /* 其他参数 https://www.xstnet.com/article-108.html
                weight（整数）：主机的权重与其他主机的权重相比较，以便在多个主机上自定义会话分配。如果主机A的重量是主机B的两倍，它将获得两倍的会话数量。在该示例中，host1存储所有会话的20％（1 /（1 + 2 + 2）），而host2和-
                host3每个存储40％（2 /（1 + 2 + 2））。目标主机在会话开始时一劳永逸地确定，并且不会更改。默认权重为1。
                timeout（float）：redis主机的连接超时，以秒为单位。如果主机在该时间内无法访问，则会话存储将不可用于客户端。默认超时非常高（86400秒）。
                persistent（整数，应该是1或0）：定义是否应该使用持久连接。（实验设定）
                prefix（字符串，默认为“PHPREDIS_SESSION：”）：用作存储会话的Redis密钥的前缀。密钥由前缀后跟会话ID组成。
                auth（字符串，默认为空）：用于在发送命令之前对服务器进行身份验证。
                database（整数）：选择不同的数据库。
             */
            $redisParams = [];
            if(is_numeric($redisDatabase) && $redisDatabase > 0) array_push($redisParams, 'database=' . $redisDatabase);
            if(strlen($redisAuth) > 0) array_push($redisParams, 'auth=' . $redisAuth); //$redisParams['auth'] = $redisAuth;

            if(count($redisParams) > 0)  $savePath .= '?' . implode('&', $redisParams);// tcp://127.0.0.1:6379?auth=authpwd
            ini_set("session.save_handler", "redis");
            ini_set("session.save_path", $savePath);
        }
        // 24 * 5个钟头
        ini_set('session.gc_maxlifetime',static::getGcMaxlifetime());
        //开启会话
        if (!session_id()) session_start(); // 初始化session
    }

    /**
     * 设置session,返回session_id
     * 每次调用set方法，session会自动把session的有效期自动重新设置为 gc_maxlifetime
     * @param String $name   session name
     * @param Mixed  $data   session data
     * @param Int    $expire 超时时间(秒) 默认 10分钟 session中具体某个下标的缓存时间，
     *                 <= 0 时 默认使用 SessionCustom::getGcMaxlifetime();的值
     *                如果要与session一样的有效期，请使用  SessionCustom::getGcMaxlifetime();
     * $redisConfig = [
     *   'host' => 'localhost',// 主机 ：无下标【使用属性指定的】
     *   'port' => 6379,// 端口 ：无下标【使用属性指定的】
     *   'auth' => '',// 密码：无下标【使用属性指定的】
     *   'database' => 2,//  无下标：-1【使用属性指定的】； （整数）：选择不同的数据库。 < 0 时：使用配置，配置没有再使用默认指定的
     * ];
     * @return string session的 id
     */
    public static function set($name, $data, $expire = 600, $redisConfig = []){
        if(!is_numeric($expire) || $expire <= 0) $expire = static::getGcMaxlifetime();// 默认值
        $session_data = array();
        $session_data['data'] = $data;
        // $currentTime = Carbon::now();// date('Y-m-d H:i:s');//当前时间 2020-06-02 15:48:49
        $expireTime = Carbon::now()->addSeconds($expire)->toDateTimeString();// 最后的有效期  格式  2020-06-02 15:48:49
        $session_data['expire'] = $expire;// 有效期 (秒)   time() + $expire;
        $session_data['endTime'] = $expireTime;// 到期时间点 格式  2020-06-02 15:48:49
        static::sesseionStart($redisConfig);
        // 写入会话
        $_SESSION[$name] = $session_data;
        //写入会话后关闭上一个会话文件的写入
        session_write_close();
        return session_id();// sess_jta3efe5ggeraemo7aofa1itps  中的 后面部分  jta3efe5ggeraemo7aofa1itps
        // redis缓存时 PHPREDIS_SESSION:jta3efe5ggeraemo7aofa1itps 中的后面部分  jta3efe5ggeraemo7aofa1itps
    }

    /**
     *
     * 普通获取样例
     *  SessionCustom::get('test')
     *
     * 自动续期样例
     * $data = '12345678';
     * $aaa = 'get';
     * SessionCustom::get('test', true,  function($sessionData) use(&$data, &$aaa){
     *    echo '$sessionData=' .  $sessionData . '<br/>';
     *    echo '$data=' .  $data . '<br/>';
     *    echo '$aaa=' .  $aaa . '<br/>';
     * })
     *
     * 读取session--普通的读取，没有过期就能读
     * @param  String $name  session name
     * @param  boolean $isExtendExpire  是否自动续有效期
     * @param mixed $extendExpireFun 自动续期后执行的操作--一些操作 的闭包函数  function($data){} ;// 参数 data 为缓存的数据
     * $redisConfig = [
     *   'host' => 'localhost',// 主机 ：无下标【使用属性指定的】
     *   'port' => 6379,// 端口 ：无下标【使用属性指定的】
     *   'auth' => '',// 密码：无下标【使用属性指定的】
     *   'database' => 2,//  无下标：-1【使用属性指定的】； （整数）：选择不同的数据库。 < 0 时：使用配置，配置没有再使用默认指定的
     * ];
     * @return Mixed false:失败
     */
    public static function get($name, $isExtendExpire = false, $extendExpireFun = null, $redisConfig = []){
         static::sesseionStart($redisConfig);
        if(isset($_SESSION[$name])){
            $currentTime = Carbon::now();// date('Y-m-d H:i:s');//当前时间 2020-06-02 15:48:49
            $endTime = $_SESSION[$name]['endTime'] ?? $currentTime;// 最后的有效期  格式  2020-06-02 15:48:49
            $endCarbon = carbon::parse ($endTime); // 格式化一个时间日期字符串为 carbon 对象
            // 减当前时间 ; > 0 没有过期 = 0 马上过期  < 0 过期
            $diffSeconds = (new Carbon)->diffInSeconds ($endCarbon, false); // $int 为正负数

            // if($_SESSION[$name]['expire'] > time() ){
            if($diffSeconds > 0 ){
                $data = $_SESSION[$name]['data'] ?? null;
                // 需要自动续有效期
                if($isExtendExpire){
                    $expire = $_SESSION[$name]['expire'] ?? static::getGcMaxlifetime();
                    // 还剩三分之一内的有效时间就自动续期
                    if(ceil($expire / 3) >= $diffSeconds){
                        static::set($name, $data, $expire, $redisConfig);// 自动续期
                        // 自动续期后执行的操作--一些操作
                        if(is_callable($extendExpireFun)){
                            $extendExpireFun($data);
                        }
                    }
                }
                return $data;
            }else{
                static::clear($name, $redisConfig);
            }
        }
        return false;
    }

    /**
     * 清除session
     * @param  String  $name  session name
     * $redisConfig = [
     *   'host' => 'localhost',// 主机 ：无下标【使用属性指定的】
     *   'port' => 6379,// 端口 ：无下标【使用属性指定的】
     *   'auth' => '',// 密码：无下标【使用属性指定的】
     *   'database' => 2,//  无下标：-1【使用属性指定的】； （整数）：选择不同的数据库。 < 0 时：使用配置，配置没有再使用默认指定的
     * ];
     */
    public static function clear($name, $redisConfig = []){
        static::sesseionStart($redisConfig);
        if(isset($_SESSION[$name])) unset($_SESSION[$name]);
    }
}

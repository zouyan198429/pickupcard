<?php
// 用Redis构建分布式锁-多个redis代码
namespace App\Services\Lock;


// //初始化对象
//$redisesLockObj = RedisesLock::instance({});
// 使用方法：最下面的注释有使用方式
class RedisesLock
{
    private $lockPre = 'Lock:';#锁键前缀
    //private $retryDelay;//重试延时 单位毫秒
    private $retryDelayMin = 200;#延时重新获得锁的时间 - 微秒 最小值
    private $retryDelayMax = 1000;#延时重新获得锁的时间 - 微秒 最大值 随机延时750-1500微秒 ，经测试这个范围最佳
    //private $retryCount;//重试次数
    private $clockDriftFactor = 0.01;//时钟漂移的因素

    private $quorum;//法定数量

    private static $instance;  //  私有静态属性用以保存对象
    private $servers = array();//redis服务器数组[二维] [ip,端口,连接超时]
    private $instances = array();//redis 对象集合数组

    /**
     * @desc 锁构造函数
     * @param array $servers  redis服务器数组[二维] [ip,端口,连接超时,登陆密码[默认''],数据库编号[默认0]]
    //* @param int  $retryDelay  重试延时 单位毫秒
     * @param int  $retryCount  重试次数
     * @return  null
     */
    private function __construct(array $servers)//, $retryDelay = 200, $retryCount = 30
    {
        $this->servers = $servers;

        //$this->retryDelay = $retryDelay;
        //$this->retryCount = $retryCount;

        $this->quorum  = min(count($servers), (count($servers) / 2 + 1));//法定数量
    }

    //私有属性的克隆方法 防止被克隆
    private function __clone()
    {

    }

    //静态方法 用以实例化调用
    static public function instance($config)
    {
        if (!self::$instance instanceof self)
        {
            self::$instance = new self($config);
        }
        return self::$instance;
    }
    /**
     * @desc 上锁
     * @param string  $resource  键名称
     * @param int  $ttl  锁过时间，单位是毫秒
     * @param $getwaittimeout    int|   毫秒 循环获取锁的等待超时时间，在此时间内会一直尝试获取锁直到超时，为0表示失败后直接返回不等待
     * @param mix array[一维数组][validity=剩余给操作的时间,单位毫秒,resource='键名',token='键值']，false:上锁失败
     */
    public function lock($resource, $ttl=4000,$getwaittimeout=4000)
    {
        $this->initInstances();//实例化redis对象
        $token = uniqid();//值必须在所有获取锁请求的客户端里保持唯一 获取唯一标识符 以微秒计的当前时间，生成一个唯一的 ID
        //$retry = $this->retryCount;
        $getwaittimeout = ceil($getwaittimeout); #确保是整数
        $end = time()+ceil($getwaittimeout/1000); #$end=time()+$timeout; #当前时间的秒数

        do {
            $n = 0;//记录成功锁定的数量

            $startTime = microtime(true) * 1000;//毫秒 ;  microtime返回当前 Unix 时间戳的微秒数,当设置为 TRUE 时，规定函数应该返回浮点数，否则返回字符串。默认为 FALSE。

            foreach ($this->instances as $instance) {//获得锁
                if ($this->lockInstance($instance, $resource, $token, $ttl)) {
                    $n++;
                }
            }

            # Add 2 milliseconds to the drift to account for Redis expires  加上2毫秒的漂移到redis到期精度
            # precision, which is 1 millisecond, plus 1 millisecond min drift
            # for small TTLs.
            $drift = ($ttl * $this->clockDriftFactor) + 2;//不同进程间时钟差异
            //补偿不同进程间时钟差异的delta值（一般只有几毫秒而已）
            $validityTime = $ttl - (microtime(true) * 1000 - $startTime) - $drift;//有效时间 单位毫秒

            if ($n >= $this->quorum && $validityTime > 0) {//成功获得锁，获得锁数量>=法定数量 且有有效时间
                return [
                    'validity' => $validityTime,//剩余给操作的时间,单位毫秒
                    'resource' => $resource,//锁键
                    'token'    => $token,//锁键值
                ];

            } else {//没有获得锁，则删除获得的部分锁
                foreach ($this->instances as $instance) {
                    $this->unlockInstance($instance, $resource, $token);
                }
            }

            // Wait a random delay before to retry
            //$delay = mt_rand(floor($this->retryDelay / 2), $this->retryDelay);//重试延时 单位毫秒
            $delay = mt_rand($this->retryDelayMin, $this->retryDelayMax);
            usleep($delay);//usleep($delay * 1000);//延迟代码执行若干微秒 采用随机延时是为了避免不同客户端同时重试导致谁都无法拿到锁的情况出现

            //$retry--;

        } while (time() < $end && $getwaittimeout > 0);//$retry > 0

        return false;
    }

    /**
     * @desc 解锁
     * @param array[一维数组][validity=剩余给操作的时间,单位毫秒,resource='键名',token='键值']
     * @return null
     */
    public function unlock(array $lock)
    {
        $this->initInstances();
        $resource = $lock['resource'];
        $token    = $lock['token'];

        foreach ($this->instances as $instance) {
            $this->unlockInstance($instance, $resource, $token);
        }
    }

    /**
     * @desc 实例redis对象
     * @return null
     */
    private function initInstances()
    {
        if (empty($this->instances)) {
            //$timeout 单位秒 ;如果一个master节点不可用了，我们应该尽快尝试下一个master节点
            foreach ($this->servers as $server) {
                list($host, $port, $timeout, $auth, $dbNum) = array_values($server);
                $redis = new \Redis();
                $redis->connect($host, $port, $timeout);// timeout 以秒为单位）。默认值为 15 秒。值 0 指示无限制
                if( $auth !== '' ) $redis->auth($auth); //密码验证
                if($dbNum > 0 ) $redis->select($dbNum);//选择数据库2

                $this->instances[] = $redis;
            }
        }
    }

    /**
     * @desc 上锁
     * @param obj $instance  redis对象
     * @param string  $resource  键名称
     * @param string  $token  锁值
     * @param int  $ttl  锁过时间，单位是毫秒
     * @param bool true:上锁成功，false:上锁失败
     */
    private function lockInstance($instance, $resource, $token, $ttl)
    {
        $redisKey = $this->lockPre . $resource;
        if( 1>2 ){//Redis 2.6.12 版本开始
//            $result= $instance->set($this->lockPre . $resource, $token, ['NX', 'PX' => $ttl]);
        }else{
            $timeout = ceil($ttl/1000);//向上取整秒
            $result = $instance->setnx($redisKey, $token);
            if($result) #查看$lockName是否被上锁-获得锁
            {
                $instance->expire($redisKey, $timeout);  #为$lockName设置过期时间，防止死锁
            }
            elseif ($instance->ttl($redisKey) === -1)#没有获得锁 当key不存在或没有设置生存时间时，返回-1
            {
                $instance->expire($redisKey, $timeout);  #检测是否有设置过期时间，没有则加上（假设，客户端A上一步没能设置时间就进程奔溃了，客户端B就可检测出来，并设置时间）
            }
        }
        return $result;
    }

    /**
     * @desc 解锁
     * @param obj $instance  redis对象
     * @param string  $resource  键名称
     * @param string  $token  锁值
     * @param int 0 或有键且值也相等，则删除键
     */
    private function unlockInstance($instance, $resource, $token)
    {
        if(1>2){
//            $script = '
//            if redis.call("GET", KEYS[1]) == ARGV[1] then
//                return redis.call("DEL", KEYS[1])
//            else
//                return 0
//            end
//        ';
//            $result = $instance->eval($script, [$this->lockPre . $resource, $token], 1);

        }else{
            $redisKey = $this->lockPre . $resource;//redis锁键
            if($instance->get($redisKey) == $token) #判断是锁有没有被其他客户端修改-没有修改，则删除
            {
                $instance->multi();
                $instance->del($redisKey); #释放锁
                $instance->exec();
                $result = true;
            }
            else#已经修改,已经是别人的锁
            {
                $result = 0;// false; #其他客户端修改了锁，不能删除别人的锁
            }
        }
        return $result;
    }
}

/*
 *

require_once __DIR__ . '/src/RedLock.php';
//$timeout 单位秒 ;如果一个master节点不可用了，我们应该尽快尝试下一个master节点
$servers = [
    //['127.0.0.1', 6379, 0.01, '', 0],
    ['192.168.56.114', 6379, 0.01, '', 0],
    //['172.29.8.165', 6379, 0.01, '', 0],
    //['127.0.0.1', 6399, 0.01, '', 0],
];

$redLock = new RedLock($servers);
$redis_obj = new Redis();
$redis_obj->connect('192.168.56.114',6379);
$start = time();
for ($i = 0; $i < 10; $i++)
{
    $lockState = $redLock->lock('locktest', 4000,4000);//加锁
    if($lockState)
    {
        $count = $redis_obj->get('count');
        $count = $count+1;
        $redis_obj->set('count',$count);
        $redLock->unlock($lockState);//解锁
    }else{

    }
}
$end = time();
echo "this OK<br/>";
echo "执行时间为：".($end-$start);

//while (true) {
//    $lock = $redLock->lock('test', 10000);
//
//    if ($lock) {
//        print_r($lock);
//    } else {
//        print "Lock not acquired\n";
//    }
//}
 *
 */


/*
$lockObj = Tool::getLockRedisesLaravelObj();// ->test('lock_count');

$redis_obj = new \Redis();
$redis_obj->connect('localhost',6379);
$redis_obj->auth('ABCabc123456!@#'); //密码验证
$redis_obj->select(0);//选择数据库2
$start = time();
for ($i = 0; $i < 10; $i++)
{
    $lockState = $lockObj->lock('locktest', 4000,4000);//加锁
    if($lockState)
    {
        $count = $redis_obj->get('count');
        echo '$i=' .  $count .'<br/>';
        $count = $count+1;
        $redis_obj->set('count',$count);
        $lockObj->unlock($lockState);//解锁
    }else{

    }
}
$end = time();
echo "this OK<br/>";
echo "执行时间为：".($end-$start);
die();
*/
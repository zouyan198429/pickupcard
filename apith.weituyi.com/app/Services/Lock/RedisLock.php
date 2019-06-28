<?php
namespace App\Services\Lock;

// 单redis锁
// //初始化对象
//$redisLockObj = RedisLock::instance({});
// 使用：请看下面的 test方法
class RedisLock
{
    private static $instance;  //  私有静态属性用以保存对象
    private $config;

    private $lockPre = 'Lock:';#锁键前缀
    private $redis = ''; #存储redis对象
    private $retryDelayMin = 500;#延时重新获得锁的时间 - 微秒 最小值
    private $retryDelayMax = 1800;#延时重新获得锁的时间 - 微秒 最大值 随机延时750-1500微秒 ，经测试这个范围最佳

    /**
     * //私有属性的构造方法 防止被 new
     * @desc 构造函数
        $config =[
            'host' => '',// 默认 localhost
            'port' => '',// 默认 6379
            'auth' => '',// 默认空
            'dbNum' => 0,// 默认 0
        ];
     * @param $host string | redis主机
     * @param $port int | 端口
     */
    private function __construct($config)
    {
        $this->config = $config;
        $host = $config['host'] ?? 'localhost';
        $port = $config['port'] ?? 6379;
        $auth = $config['auth'] ?? '';
        $dbNum = $config['dbNum'] ?? 0;

        $this->redis = new \Redis();
        $this->redis->connect($host, $port);

        if( $auth !== '' ) $this->redis->auth($auth); //密码验证
        if($dbNum > 0 ) $this->redis->select($dbNum);//选择数据库2
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
     * @desc 加锁方法
     *
     * @param $lockName string | 锁的名字 最好唯一性
     * @param $timeout int | 锁的过期时间 秒数
     * @param $getwaittimeout    int|    循环获取锁的等待超时时间，在此时间内会一直尝试获取锁直到超时，为0表示失败后直接返回不等待
     *
     * @return mixed 成功返回identifier/失败返回false
     */
    public function getLock($lockName, $timeout = 2, $getwaittimeout = 2)
    {
        # 1 秒 = 1000 毫秒  1毫秒=1000微秒
        $identifier = uniqid() ;#. sprintf("%06d",mt_rand(1,999999));  #获取唯一标识符 以微秒计的当前时间，生成一个唯一的 ID
        $timeout = ceil($timeout); #确保是整数
        $getwaittimeout = ceil($getwaittimeout); #确保是整数
        $end = time()+$getwaittimeout; #$end=time()+$timeout; #当前时间的秒数
        $redisKey = $this->lockPre . $lockName;//redis锁键
        while(true)   #循环获取锁
        {
            if($this->redis->setnx($redisKey, $identifier)) #查看$lockName是否被上锁-获得锁
            {
                $this->redis->expire($redisKey, $timeout);  #为$lockName设置过期时间，防止死锁
                return $identifier;        #返回一维标识符
            }
            elseif ($this->redis->ttl($redisKey) === -1)#没有获得锁 当key不存在或没有设置生存时间时，返回-1
            {
                $this->redis->expire($redisKey, $timeout);  #检测是否有设置过期时间，没有则加上（假设，客户端A上一步没能设置时间就进程奔溃了，客户端B就可检测出来，并设置时间）
            }
            if (time() > $end || $getwaittimeout <= 0){#$getwaittimeout<=0不用等待,或时间超过等待时间，退出等待锁
                break;
            }
            //延时处理
            //$delay = mt_rand(floor($this->retryDelay / 2), $this->retryDelay); #返回随机整数,可以防止并发的再次并发去获得锁
            $delay = mt_rand($this->retryDelayMin, $this->retryDelayMax);
            usleep($delay);#usleep($delay/1000);#usleep($delay * 1000);#usleep(0.001);   #停止0.001ms 延迟代码执行若干微秒
        }
        return false;
    }

    /**
     * @desc 释放锁
     *
     * @param $lockName string | 锁名
     * @param $identifier string | 锁的唯一值
     *
     * @param bool true:自己的锁，删除锁，false:别人的锁，不操作
     */
    public function releaseLock($lockName, $identifier)
    {
        $redisKey = $this->lockPre . $lockName;//redis锁键
        if($this->redis->get($redisKey) == $identifier) #判断是锁有没有被其他客户端修改-没有修改，则删除
        {
            $this->redis->multi();
            $this->redis->del($redisKey); #释放锁
            $this->redis->exec();
            return true;
        }
        else#已经修改,已经是别人的锁
        {
            return false; #其他客户端修改了锁，不能删除别人的锁
        }
    }
    /**
     * @desc 测试
     *
     * @param $lockName string | 锁名
     */
    public function test($lockName)
    {
        $start = time();
        for ($i=0; $i < 10; $i++)
        {
            $identifier = $this->getLock($lockName,2,2);
            if($identifier)
            {
                $count = $this->redis->get('count');
                // echo '第' . $i . '次 = ' . $count . '<br/>';
                $count = $count+1;
                $this->redis->set('count',$count);
                $this->releaseLock($lockName,$identifier);
            }
        }
        $end = time();
        echo "this OK <br/>";
        echo "执行时间为：" . ($end - $start);
    }
}

//header("content-type: text/html;charset=utf8;");
//$obj=new Lock('172.29.8.165');
//$obj->test('lock_count');
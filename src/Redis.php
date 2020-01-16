<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2019/12/26
 * Time: 10:04
 */

namespace Daishu;

use Daishuwx\Config;

class Redis
{
    private static $instance=null;

    public $redis=null;

    public $lockValue;


    private function __construct()
    {
        $this->redis=new \Redis();
        $redisConfig = Config::get('redis');
        $status=$this->redis->connect($redisConfig['host'],$redisConfig['port'],$redisConfig['time_out']);
        if ($status===false){
            throw new \Exception('redis connect error');
        }
    }


    public static function getInstance(){
        if (self::$instance==null){
            self::$instance=new self();
        }
        return self::$instance->redis;
    }

    /**
     * redis锁
     * @param $key
     * @return bool
     *User: ligo
     */
    public function __lock($key)
    {
        $time = microtime(true);
        $sleepTime = 1000;
        $waitTimeout = 0;
        $guid = uniqid('', true);
        while(true)
        {
            $value = json_decode($this->redis->get($key), true);
            $this->lockValue = array(
                'expire'	=>	time() + 8,
                'guid'		=>	$guid,
            );
            if(null === $value)
            {
                // 无值
                $result = $this->redis->setnx($key, json_encode($this->lockValue));
                if($result)
                {
                    $this->redis->expire($key, 10);
                    return true;
                }
            }
            else
            {
                // 有值
                if($value['expire'] < time())
                {
                    $result = json_decode($this->redis->getSet($key, json_encode($this->lockValue)), true);
                    if($result === $value)
                    {
                        $this->redis->expire($key, 10);
                        return true;
                    }
                }
            }
            if(0 === $waitTimeout || microtime(true) - $time < $waitTimeout)
            {
                usleep($sleepTime);
            }
            else
            {
                break;
            }
        }
        return false;
    }

    /**
     * 解锁
     * @param $key
     * @return bool
     *User: ligo
     */
    public function __unlock($key)
    {

        if((isset($this->lockValue['expire']) && $this->lockValue['expire'] > time()))
        {
            return $this->redis->del($key) > 0;
        }
        else
        {
            return true;
        }
    }
}
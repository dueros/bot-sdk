<?php

class RedisHelper
{

    private $redis; //到redis server 的连接
    private $redis_server;
    private $redis_port;
    private $redis_pwd;

    /**
     * @desc 构造函数，从配置文件里读取用户配置的serverip  port，初始化相应的变量
     * @param null
     * @return null
     * */
    public function __construct()
    {
        $this->redis_server = $this->getConfig('redis_server');
        $this->redis_port = $this->getConfig('redis_port');
        $this->redis_pwd = $this->getConfig('redis_pwd');

        //用户没有配置时，默认的server和port

        if (empty($this->redis_server)) {
            $this->redis_server = '127.0.0.1';
        }
        if (empty($this->redis_port)) {
            $this->redis_port = 6379;
        }


    }

    /**
     * @desc 用于测试能否正确从文件读到配置
     * @param null
     * @return null
     * */
    public function printParams()
    {
        var_dump($this->redis_server);
        var_dump($this->redis_port);
        var_dump($this->redis_pwd);
    }

    /**
     * @desc 获取到redis server的连接
     * @param null
     * @return true：连接成功； false：连接失败；
     * */
    public function getConnection()
    {
        $this->redis = new Redis();
        $con = $this->redis->connect($this->redis_server, $this->redis_port);
        //密码不为空时，校验密码
        return $con;
    }

    //普通key-value 相关的接口
    /**
     * @desc
     * @param $key
     * @return 
     * */
    public function getValue($key)
    {
        return $this->redis->get($key);
    }


    /**
     * @desc
     * @param $key
     * @param $value
     * @return 
     * */
    public function setValue($key, $value)
    {
        return $this->redis->set($key, $value);
    }

    /**
     * @desc 
     * @param $key 
     * @return 
     * */
    public function del($key)
    {
        return $this->redis->delete($key);
    }

    /**
     * @desc
     * @param $key
     * @return 
     * */
    public function exists($key)
    {
        return $this->redis->exists($key);
    }


    /**
     * 下面是hset 相关的接口
     * hset 数据类型， 对应到比赛计分器则是：
     * key 对应计分用户的data_userid.
     * field 对应选手，如张三
     * value 则是对应选手的分数
     * hset('data_213456', '张三'，0) 则表示记分员213456为他的比赛增加了选手张三
     * */


    /**
     * @desc 给key下字段field设置值value
     * @param $key
     * @param $field
     * @param $value
     * @return 1:对新的域赋值  0：对已有域的值更新
     * */
    public function hset($key, $field, $value)
    {
        return $this->redis->hset($key, $field, $value);
    }


    /**
     * @desc 获取key下的所有字段及其对应的值
     * @param $key
     * @return 一个包含 ( 域 => value) 的数组
     * */
    public function hgetall($key)
    {
        return $this->redis->hgetall($key);
    }

    /**
     * @desc 给键key下field字段值增加value
     * @param $key 
     * @param $field
     * @param $value
     * @return 增加后的值
     * */
    public function hincrby($key, $field, $value)
    {
        return $this->redis->hincrby($key, $field, $value);
    }

    /**
     * @desc 获取key下某个field的值
     * @param $key
     * @param $field
     * @return key下field域的值
     * */
    public function hget($key, $field)
    {
        return $this->redis->hget($key, $field);
    }

    /**
     * @desc 删除key下的指定字段
     * @param $key
     * @param $field
     * @return 1:制定域存在且删除成功  0:删除失败
     * */
    public function hdel($key, $field)
    {
        return $this->redis->hdel($key, $field);
    }

    /**
     * @desc 判断key下是否存在某个字段
     * @param $key 指定的key
     * @param $field 要判断的字段
     * @return true:存在  false:不存在
     * */
    public function hexists($key, $field)
    {
        return $this->redis->hexists($key, $field);
    }

    /**
     * @desc 获取hset数据类型一个key下包含的所有field
     * @param $key 要查询的key
     * @return 当前key下的所有field的数组
     * */
    public function hkeys($key)
    {
        return $this->redis->hkeys($key);
    }

    /**
     * @desc 关闭redis连接
     * @param null
     * @return null
     * */
    public function closeConnection()
    {
        if ($this->redis) {
            $this->redis->close();
        }
    }

    /**
     * @desc 用于从配置文件读取用户配置的serverip,port
     * @param $key 要获取的配置项名称
     * @return 指定配置项的值
     * */
    public function getConfig($key)
    {
        $valid_params = array('redis_server', 'redis_port', 'redis_pwd');
        if (!in_array($key, $valid_params)) {
            return null;
        }

        $data = file_get_contents('server.config');
        $configs = array_unique(array_map('trim', explode(';', $data)));

        foreach ($configs as $config) {
            $param_value = explode(':', $config);
            $param = array_shift($param_value);
            if ($param == $key) {
                return trim(array_shift($param_value));
            } else {
                continue;
            }
        }
    }
}

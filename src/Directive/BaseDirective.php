<?php
namespace Baidu\Duer\Botsdk\Directive;

abstract class BaseDirective{
    protected $data=[];
    /**
     * @param string $type 指令类型
     * @return null
     **/
    public function __construct($type) {
        $this->data['type']= $type;
    }

    /**
     * @desc 生成token.  通过时间+随机+md5来生成一个伪唯一的token
     **/
    protected function genToken(){
        $str = microtime(1) . '' . rand(10000, 99999); 
        return md5($str);
    }

    /**
     * @param null
     * @return array
     **/
    public function getData(){
        return $this->data; 
    }
}
 

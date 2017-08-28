<?php
namespace Baidu\Duer\Botsdk\Directive;

abstract class BaseDirective{
    protected $data = [];
    /**
     * @param string $type 指令类型
     * @return null
     **/
    public function __construct($type) {
        $this->data['type']= $type;
    }

    /**
     * @desc 生成token.  生成一个伪唯一的token
     **/
    protected function genToken(){
        $str = md5(uniqid(mt_rand(), true));  
        $uuid  = substr($str,0,8) . '-';  
        $uuid .= substr($str,8,4) . '-';  
        $uuid .= substr($str,12,4) . '-';  
        $uuid .= substr($str,16,4) . '-';  
        $uuid .= substr($str,20,12);  
        return $uuid;
    }

    /**
     * @param null
     * @return array
     **/
    public function getData(){
        return $this->data; 
    }
}
 

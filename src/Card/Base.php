<?php
namespace Baidu\Duer\Botsdk\Card;

abstract class Base{
    protected $data=[];
    protected $supportSetField = [];
    /**
     * @param array $fields
     * @return null
     **/
    public function __construct($fields=[]) {
        $this->supportSetField = $fields;
    }

    /**
     * @param array|string $data 比如：['###', '###',...,'###'], 或者'###'
     * @return self
     **/
    public function addCueWords($arr=[]){
        if($arr) {
            if(is_string($arr)) {
                $arr = [$arr]; 
            }

            $this->data['cueWords'] = $this->data['cueWords']?$this->data['cueWords']:[];
            $this->data['cueWords'] = array_merge($this->data['cueWords'], $arr);
        }

        return $this;
    }

    /**
     * @param string $url 比如:http(s)://....
     * @param boolean $text 链接显示的文字
     * @return self
     **/
    public function setAnchor($url, $text=''){
        if($url) {
            $this->data['url'] = $url; 

            if($text) {
                $this->data['anchorText'] = $text; 
            }
        }

        return $this;
    }

    /**
     * @param string $key 字段名
     * @return array|null
     **/
    public function getData($key=''){
        if($key) {
            return $this->data[$key]; 
        }

        return $this->data; 
    }

    public function __call($name, $arguments){
        /**
         * 将规定的field 通过setFieldName('content')来设置
         **/
        $operation = substr($name, 0, 3);
        $field = lcfirst(substr($name, 3));
        if($operation == 'set' && in_array($field, $this->supportSetField)) {
            $this->data[$field] = $arguments[0];
            return $this;
        }
    }
}
 

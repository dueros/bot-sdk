<?php
namespace Baidu\Duer\Botsdk\Card;

abstract class Base{
    protected $data=[];
    /**
     * @param array $data
     * @param boolean $anchor 是否有链接
     * @return null
     **/
    public function __construct($data, $anchor=true) {
        $this->cueWords($data['cueWords']);
        if($anchor) {
            $this->anchor($data['url'], $data['anchorText']);
        }
    }

    /**
     * @param array $data 比如：['###', '###',...,'###']
     * @return self
     **/
    public function cueWords($arr=[]){
        if($arr) {
            $this->data['cueWords'] = $arr;
        }

        return $this;
    }

    /**
     * @param string $url 比如:http(s)://....
     * @param boolean $text 链接显示的文字
     * @return self
     **/
    public function anchor($url, $text=''){
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
}
 

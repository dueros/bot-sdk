<?php
/**
 * 特性。支持array.a.b 的get和set
 * @author wangpeng01@baidu.com
 **/
namespace Baidu\Duer\Botsdk;
trait DataObject{
    /**
     * 数据
     **/
    private $data=[];

    /**
     * 获取值
     * @desc 获取a.b.c 对应 array[a][b][c]的值
     * @param string $field 属性名
     * @param string $default 默认值，如果对应属性值为空，使用$default返回
     * @return mixed
     **/
    public function getData($field=null,$default=null){
        if(is_null($field)){
            return $this->data;
        }
        $tmp=$this->data;
        foreach(explode(".",$field) as $f){
            if(!isset($tmp[$f])){
                return $default;
            }
            $tmp=$tmp[$f];
        }
        return $tmp;
    }

    /**
     * @desc 设置a.b.c 对应 array[a][b][c]的值
     * @param string $field 属性名
     * @param mixed $value 值
     * @param string $default  默认值，如果值为空，使用$default
     * @return null
     **/
    public function setData($field,$value,$default=null){
        if(empty($field)){
            return;
        }
        $tmp=&$this->data;
        $fs=explode(".",$field);
        $last_f=array_pop($fs);
        foreach($fs as $f){
            if(!isset($tmp[$f])){
                $tmp[$f]=[];
            }
            $tmp=&$tmp[$f];
        }
        if(!is_null($default) && empty($value)){
            $value=$default;
        }
        $tmp[$last_f]=$value;
    }
}

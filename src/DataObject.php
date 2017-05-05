<?php
namespace Baidu\Duer\Botsdk;
trait DataObject{
    private $data=[];
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

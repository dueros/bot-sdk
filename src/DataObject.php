<?php
/**
 * Copyright (c) 2017 Baidu, Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @desc 特性。支持array.a.b 的get和set
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

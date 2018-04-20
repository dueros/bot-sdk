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
 * @desc 卡片的基类
 **/
namespace Baidu\Duer\Botsdk\Card;
use Baidu\Duer\Botsdk\Utils;

abstract class BaseCard{
    protected $data=[];
    protected $supportSetField = [];
    /**
     * @param array $fields 允许通过魔术方法设置的字段
     * @return null
     **/
    public function __construct($fields=[]) {
        $this->supportSetField = $fields;
        $this->data['token'] = Utils::genToken();
    }

    /**
     * @desc 设置卡片token
     * @param string $token
     **/
    public function setToken($token){
        if(is_string($token) && $token) {
            $this->data['token'] = $token; 
        }
    }

    /**
     * @desc 为卡片添加cue words 提示用户输入
     * @param array|string $arr 比如：['###', '###',...,'###'], 或者'###'
     * @return self
     **/
    public function addCueWords($arr){
        if($arr) {
            if(is_string($arr)) {
                $arr = [$arr]; 
            }

            $this->data['cueWords'] = isset($this->data['cueWords'])?$this->data['cueWords']:[];
            $this->data['cueWords'] = array_merge($this->data['cueWords'], $arr);
        }

        return $this;
    }

    /**
     * @desc 设置卡片链接
     * @param string $url 比如:http(s)://....
     * @param boolean $anchorText 链接显示的文字
     * @return self
     **/
    public function setAnchor($url, $anchorText=''){
        if($url) {
            $this->data['url'] = $url; 

            if($anchorText) {
                $this->data['anchorText'] = $anchorText; 
            }
        }

        return $this;
    }

    /**
     * @param string $key 字段名。如果没有返回整个数据
     * @return array|null
     **/
    public function getData($key=''){
        if($key) {
            return $this->data[$key]; 
        }

        return $this->data; 
    }

    /**
     * @desc 魔术方法
     * @param string $name
     * @param array $arguments
     * @return null | $this;
     **/
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

        throw new \Exception("$name function not found");
    }
}
 

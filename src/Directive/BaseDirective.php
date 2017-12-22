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
 * @desc 指令类的基类
 **/
namespace Baidu\Duer\Botsdk\Directive;

abstract class BaseDirective{
    protected $data = [];
    /**
     * @param string $type 指令类型
     * @return null
     **/
    public function __construct($type) {
        $this->data['type'] = $type;
    }

    /**
     * 生成token
     * @desc 生成token.  生成一个伪唯一的token
     * @return string
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
     * 获取命令的数据
     * @param null
     * @return array
     **/
    public function getData(){
        return $this->data; 
    }
}
 

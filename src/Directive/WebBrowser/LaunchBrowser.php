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
 * @desc 用于调用浏览器指令的类
 **/
namespace Baidu\Duer\Botsdk\Directive\WebBrowser;

class LaunchBrowser extends \Baidu\Duer\Botsdk\Directive\BaseDirective{

    /**
     * @param string $url 连接地址
     *
     * @return null
     **/
    public function __construct($url) {
        parent::__construct('WebBrowser.LaunchBrowser');

        $this->data['url'] = $url;
        $this->data['token'] = $this->genToken();
    }

    /**
     * @desc 设置directive的token. 默认在构造时自动生成了token，可以覆盖
     * @param string $token token
     * @return null
     **/
    public function setToken($token){
        if($token) {
            $this->data['token'] = $token;
        }
    }

    /**
     * @desc 获取directive的token. 默认在构造时自动生成了token
     * @param null
     * @return string
     **/
    public function getToken(){
        return $this->data['token'];
    }
}

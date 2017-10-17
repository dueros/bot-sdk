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
 * @desc 用于调用app的指令类
 **/
namespace Baidu\Duer\Botsdk\Directive\AppLauncher;

class LaunchApp extends \Baidu\Duer\Botsdk\Directive\BaseDirective{

    /**
     * @param string $appName 应用的名称
     * @param string $packageName 应用的包名
     * @param string $deepLink 打开应用指定功能
     *     注意：以上appName，packageName和deepLink三个参数至少一个
     *
     * @return null
     **/
    public function __construct($appName = '', $packageName = '', $deepLink = '') {
        parent::__construct('AppLauncher.LaunchApp');

        $this->data = array_merge($this->data, [
            'appName' => $appName,
            'packageName' => $packageName,
            'deepLink' => $deepLink,
            'token' => $this->genToken(),
        ]);
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

    /**
     * @desc 设置应用的名称
     * @param string $appName 应用的名称
     * @return null
     **/
    public function setAppName($appName){
        if($appName) {
            $this->data['appName'] = $appName;
        }
    }

    /**
     * @desc 设置应用的包名
     * @param string $packageName 应用的包名
     * @return null
     **/
    public function setPackageName($packageName){
        if($packageName) {
            $this->data['packageName'] = $packageName;
        }
    }

    /**
     * @desc 设置deepLink
     * @param string $deepLink 
     * @return null
     **/
    public function setDeepLink($deepLink){
        if($deepLink) {
            $this->data['deepLink'] = $deepLink;
        }
    }
}

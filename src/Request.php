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
 * @desc DuerOS对Bot的请求封装
 * @author yuanpeng01@baidu.com
 **/
namespace Baidu\Duer\Botsdk;

class Request {
    /**
     * 当前请求的类型，对应request.type
     **/
    private $requestType;

    /**
     * Session
     **/
    private $session;

    /**
     * UIC 用户信息
     **/
    private $arrUserProfile;

    /**
     * NLU
     **/
    private $nlu;

    /**
     * 原始数据
     **/
    private $data;

    /**
     * ??
     **/
    private $backendServiceInfo;


    /**
     * 设备信息。比如闹钟列表
     **/
    private $deviceData;

    /**
     * @desc 返回request 请求体
     * @param null
     * @return array
     **/
    public function getData(){
        return $this->data; 
    }

    /**
     * @desc 返回session实例
     * @param null
     * @return Session
     **/
    public function getSession() {
        return $this->session;
    }

    /**
     * @desc 返回nlu实例
     * @param null
     * @return Nlu
     **/
    public function getNlu(){
        return $this->nlu;
    }
    
    
    /**
     * @deprecated
     * @desc 返回设备信息
     * @param null
     * @return Nlu
     **/
    public function getDeviceData(){
        return $this->deviceData;
    }

    /**
     * 获取设备id
     * @desc 获取设备id
     * @param null
     * @return string
     **/
    public function getDeviceId() {
        if(isset($this->data['context']['System']['device']['deviceId'])){
            return $this->data['context']['System']['device']['deviceId']; 
        }
    }

	/**
     * @desc 获取来自端上报的原始设备Id
     * @param null
     * @return string
     **/
    public function getOriginalDeviceId() {
        if(isset($this->data['context']['System']['device']['originalDeviceId'])){
            return $this->data['context']['System']['device']['originalDeviceId']; 
        }
    }


    /**
     * 获取设备音频播放的状态
     *
     * @desc 获取设备音频播放的状态
     * @param null
     * @return array
     **/
    public function getAudioPlayerContext() {
        if(isset($this->data['context']['AudioPlayer'])){
            return $this->data['context']['AudioPlayer'];
        }
    }

    /**
     * 获取设备app安装列表
     *
     * @desc 获取设备app安装列表
     * @param null
     * @return array
     **/
    public function getAppLauncherContext() {
        if(isset($this->data['context']['AppLauncher'])){
            return $this->data['context']['AppLauncher'];
        }
    }

    /**
     * 获取event请求
     *
     * @desc 返回event request数据
     * @param null
     * @return array
     **/
    public function getEventData() {
        if($this->requestType == 'IntentRequest'
           || $this->isSessionEndedRequest()
           || $this->isLaunchRequest()) {
              return; 
           }

        return $this->data['request'];
    }

    /**
     * @desc 获取用户信息
     * @param null
     * @return array
     **/
    public function getUserInfo() {
        if(isset($this->data['context']['System']['user']['userInfo'])){
            return $this->data['context']['System']['user']['userInfo'];
        }
    }
    
    /**
     * @desc 获取百度uid
     * @param null
     * @return array
     **/
    public function getBaiduUid() {
        if(isset($this->data['context']['System']['user']['userInfo']['account']['baidu']['baiduUid'])){
            return $this->data['context']['System']['user']['userInfo']['account']['baidu']['baiduUid'];
        }
    }

    /**
     * 获取request类型
     * @param null
     * @return string 
     */
    public function getType() {
        return $this->requestType;
    }


    /**
     * 获取用户id
     *
     * @param null
     * @return string
     **/
    public function getUserId() {
        if(isset($this->data['context']['System']['user']['userId'])){
            return $this->data['context']['System']['user']['userId'];
        }
    }

    /**
     * 获取accessToken
     * @param null
     * @return string
     **/
    public function getAccessToken() {
        if(isset($this->data['context']['System']['user']['accessToken'])){
            return $this->data['context']['System']['user']['accessToken'];
        }
    }

    /**
     * 获取externalAccessTokens
     * @param null
     * @return array
     **/
    public function getExternalAccessTokens() {
        if(isset($this->data['context']['System']['user']['externalAccessTokens'])){
            return $this->data['context']['System']['user']['externalAccessTokens'];
        }
    }

    /**
     * @deprecated
     * @desc 获cuid
     * @param null
     * @return string
     **/
    public function getCuid() {
        return $this->data['cuid'];
    }


    /**
     * 获取query
     * @desc 获取当前请求的query
     *
     * @param null
     * @return string
     **/
    public function getQuery() {
        if($this->requestType == 'IntentRequest' && isset($this->data['request']['query']['original'])) {
            return $this->data['request']['query']['original'];
        }
        return '';
    }


    /**
     * 获取地址
     * @desc 获取当前用户设备的位置信息。具体协议参考连接TODO
     *
     * @param null
     * @return array
     **/
    public function getLocation() {
        if(isset($this->data['context']['System']['user']['userInfo']['location'])) {
            return $this->data['context']['System']['user']['userInfo']['location']; 
        }
    }

	/**
     * @desc 
     * @param null
     * @return array|bool
     **/
    public function isDetermined() {
        if($this->requestType == 'IntentRequest' && isset($this->data['request']['determined'])) {
            return $this->data['request']['determined'];
        }
        return false;
    }
    
    /**
     * 是否为调起bot
     *
     * @desc 是否为调起bot请求
     * @param null
     * @return boolean
     **/
    public function isLaunchRequest(){
        return $this->data['request']['type'] == 'LaunchRequest';
    }

    /**
     * @deprecated
     * @param null
     * @return boolean
     **/
    public function isSessionEndRequest(){
        return $this->data['request']['type'] == 'SessionEndedRequest';
    }

    /**
     * 是否为关闭bot请求
     *
     * @param null
     * @return boolean
     **/
    public function isSessionEndedRequest(){
        return $this->isSessionEndRequest();
    }

    /**
     * 获取请求的时间戳
     *
     * @return string
     */
    public function getTimestamp() {
		if(isset($this->data['request']['timestamp'])){
			return $this->data['request']['timestamp'];
		}
    }

    /**
     * 获取log_id
     *
     * @param null
     * @return string
     */
    public function getLogId() {
        return isset($this->data['request']['requestId'])?$this->data['request']['requestId']:null;
    }
    
    /**
     * 获取botid
     *
     * @param null
     * @return string
     **/
    public function getBotId() {
        if(isset($this->data['context']['System']['application']['applicationId'])){
            return $this->data['context']['System']['application']['applicationId'];
        }
    }

    /**
     * 槽位是否填完
     *
     * @desc 填槽型多轮，当槽位补充完整后
     *       如果设置了slot confirm或者intent confirm，这些都执行完成后 
     *       对话状态设置为完成，这个函数判断是否为这个状态。
     * @param null
     * @return boolean
     **/
    public function isDialogStateCompleted(){
        return $this->data['request']['dialogState'] == 'COMPLETED';
    }

    /**
     * 构造函数
     *
     * @param array
     * @return null
     **/
    public function __construct($data) {
        $this->data = $data;
        $this->requestType = $data['request']['type'];
        $this->session = new Session($data['session']);
        if($this->requestType == 'IntentRequest') {
            $this->nlu = new Nlu($data['request']['intents']);
        }
    }
}


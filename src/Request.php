<?php
/**
 * DuerOS对Bot的请求封装
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
     * @desc 返回request data
     * @param null
     * @return array
     **/
    public function getData(){
        return $this->data; 
    }

    /**
     * @desc 返回用户信息
     * @param null
     * @return array
     **/
    public function getUserProfile() {
        return $this->arrUserProfile;
    }

    /**
     * @desc 返回session
     * @param null
     * @return Session
     **/
    public function getSession() {
        return $this->session;
    }

    /**
     * @desc 返回nlu
     * @param string $domain
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
     * @desc 获取设备id
     * @param null
     * @return string
     **/
    public function getDeviceId() {
        return $this->data['context']['System']['device']['deviceId']; 
    }

    /**
     * @desc 获取设备音频播放的状态
     * @param null
     * @return array
     **/
    public function getAudioPlayerContext() {
        return $this->data['context']['AudioPlayer']; 
    }

    /**
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
     * @param null
     * @return array
     **/
    public function getUserInfo() {
        return $this->data['user_info'];
    }
    
    public function getType() {
        return $this->requestType;
    }


    /**
     * @param null
     * @return string
     **/
    public function getUserId() {
        return $this->data['context']['System']['user']['userId']; 
    }

    /**
     * @desc 获cuid
     * @param null
     * @return string
     **/
    public function getCuid() {
        return $this->data['cuid'];
    }


    /**
     * @param null
     * @return string
     **/
    public function getQuery() {
        if($this->requestType == 'IntentRequest') {
            return $this->data['request']['query']['original'];
        }
        return '';
    }


    /**
     * @param null
     * @return array
     **/
    public function getLocation() {
        return $this->data['location'];
    }
    



    /**
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
     * @desc call isSessionEndRequest
     * @param null
     * @return boolean
     **/
    public function isSessionEndedRequest(){
        return $this->isSessionEndRequest();
    }

    /**
     * 获取log_id
     * @param null
     * @return string
     */
    public function getLogId() {
        return $this->data['log_id'];
    }
    
    /**
     * @param null
     * @return string
     **/
    public function getBotId() {
        return $this->data['context']['System']['application']['applicationId']; 
    }

    /**
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


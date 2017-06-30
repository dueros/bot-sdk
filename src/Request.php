<?php
/**
 * 中控对Bot的请求封装
 * @author yuanpeng01@baidu.com
 **/
namespace Baidu\Duer\Botsdk;

class Request {
    private $requsetType;

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
     * @param $null
     * @return raw_msg_body
     */
    public function getRawMsg()
    {
        return $this->data['msg'];
    }
    
    /**
     * @desc 返回设备信息
     * @param null
     * @return Nlu
     **/
    public function getDeviceData(){
        return $this->deviceData;
    }

    /**
     * @desc 返回app版本
     * @param null
     * @return Nlu
     **/
    public function getAppVer() {
        return $this->data['msg']['app_ver'];
    }

    /**
     * @param null
     * @return string
     **/
    public function getBduss(){
        return $this->data['msg']['bduss'];
    }

    /**
     * @param null
     * @return string
     **/
    public function getSessionTag(){
        return $this->data['params']['session_tag'];
    }

    /**
     * @deprecated
     * @param null
     * @return string
     **/
    public function getSearchBoxVer() {
        if (empty($this->data['msg']['searchbox_ver'])) {
            return '';
        }
        return $this->data['msg']['searchbox_ver'];
    }

    /**
     * @deprecated
     * @param null
     * @return string
     **/
    public function getOperationSystem() {
        if (empty($this->data['msg']['operation_system'])) {
            return '';
        }
        return $this->data['msg']['operation_system'];
    }
    
    /**
     * @param null
     * @return array
     **/
    public function getUserInfo() {
        return $this->data['user_info'];
    }

    /**
     * @param null
     * @return array
     **/
    public function getDaQueryInfo() {
        return $this->daQueryInfo;
    }
    
    /**
     * @deprecated
     * @param string
     * @return array
     **/
    public function getBackendServiceInfo($type = "all") {
        if (empty($type) || $type == "all") {
            return $this->backendServiceInfo;
        }
        if (!is_array($this->backendServiceInfo)) {
            return [];
        }
        if (!isset($this->backendServiceInfo[$type])) {
            return [];
        }
        return $this->backendServiceInfo[$type];
    }

    /**
     * 获取daqueryinfo指定服务的结果
     * @param $type da service name
     * @return array
     */
    public function getDaQueryInfoResults($type = "all") {
        if (empty($type) || $type == "all") {
            return $this->daQueryInfoResults;
        }
        if (!is_array($this->daQueryInfoResults)) {
            return [];
        }
        foreach ($this->daQueryInfoResults as $each_da_service) {
            if ($each_da_service['type'] == $type) {
                return $each_da_service;
            }
        }
        return [];
    }
    

    public function getType() {
        return $this->requsetType;
    }

    /**
     * @deprecated
     * @param null
     * @return string
     **/
    public function getRequestType() {
        return $this->data['request_type'];
    }

    /**
     * @param null
     * @return string
     **/
    public function getUserId() {
        return $this->data['user_id'];
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
    public function getDumiId() {
        return $this->data['dumi_id'];
    }

    /**
     * @param null
     * @return string
     **/
    public function getQuery() {
        if($this->requsetType == 'IntentRequest') {
            return $this->data['request']['query']['original'];
        }
        return '';
    }

    /**
     * @param null
     * @return string
     **/
    public function getQueryType() {
        if (empty($this->data['msg']['query_type'])) {
            return '';
        }
        return $this->data['msg']['query_type'];
    }

    /**
     * @param null
     * @return string
     **/
    public function getChannelFrom()
    {
        if (empty($this->data['msg']['channel_from'])) {
            return '';
        }
        return $this->data['msg']['channel_from'];
    }

    /**
     * @param null
     * @return array
     **/
    public function getLocation() {
        return $this->data['location'];
    }

    /**
     * @deprecated
     * @param null
     * @return array
     **/
    public function getLastLocation() {
        return $this->data['last_location'];
    }

    /**
     * @param null
     * @return string
     **/
    public function getClientFrom() {
        return $this->data['params']['client_from'];
    }

    /**
     * @param null
     * @return array
     **/
    public function getParam($key) {
        if (!isset($this->data['params'])) {
            return null;
        }
        if (!$key) {
            $this->data['params'];
        }
        if (!isset($this->data['params'][$key])) {
            return null;
        }
        return $this->data['params'][$key];
    }

    /**
     * 获取assistant_name
     * @param null
     * @return string
     */
    public function getAssistantName() {
        return $this->data['params']['assistant_name'];
    }

    /**
     * @desc 获取voice_asistant
     * @param null
     * @return string
     */
    public function getVoiceAssistant() {
        return $this->data['params']['voice_assistant'];
    }

    /**
     * @desc 获取appid
     * @param null
     * @return string
     */
    public function getAppid() {
        return $this->data['params']['appid'];
    }

    /**
     * @param null
     * @return boolean
     **/
    public function hasLogin() {
        return !empty($this->data['baiduid']);
    }

    /**
     * @param null
     * @return boolean
     **/
    public function isLaunchRequest(){
        return $this->data['request']['type'] == 'LaunchRequest';
    }

    /**
     * @param null
     * @return boolean
     **/
    public function isEndRequest(){
        return $this->data['request']['type'] == 'SessionEndRequest';
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
    public function getBotName() {
        return $this->data['context']['system']['bot']['botId']; 
    }

    /**
     * @param null
     * @return boolean
     **/
    public function getConfirm() {
        if($this->requsetType == 'IntentRequest') {
            return !!$this->data['request']['determined'];
        }
        return false;
    }


    /**
     * 获取baiduid
     * @param null
     * @return string
     */
    public function getBaiduId() {
        return $this->data['baiduid'];
    }

    /**
     * @param array
     * @return null
     **/
    public function __construct($data) {
        $this->data = $data;
        $this->requsetType = $data['request']['type'];
        $this->session = new Session($data['session']);
        if($this->requsetType == 'IntentRequest') {
            $this->nlu = new Nlu($data['request']['intents']);
        }
    }
}


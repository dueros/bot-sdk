<?php
/**
 * 中控对Bot的请求封装
 * @author yuanpeng01@baidu.com
 **/
namespace Baidu\Duer\Botsdk;

class Request {
    /**
     * Session
     **/
    private $session;

    /**
     * UIC 用户信息
     **/
    private $arrUserProfile;

    /**
     * NLU原始结构
     **/
    private $daQueryInfo;
    private $daQueryInfoResults;

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
    public function getNlu($domain){
        $info = $this->daQueryInfo[$domain];
        if(!$info) {
            return $info; 
        }

        return Nlu::parseQueryInfo($info);
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
    

    /**
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
        return $this->data['query'];
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
        return !!$this->data['launch'];
    }

    /**
     * @param null
     * @return boolean
     **/
    public function isEndRequest(){
        return !!$this->data['end_session'];
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
        return $this->data['bot_name']; 
    }

    /**
     * @param null
     * @return boolean
     **/
    public function getConfirm() {
        if (!isset($this->data['confirm'])) {
            return 0;
        }
        return intval($this->data['confirm']);
    }

    /**
     * @deprecated
     * @param null
     * @return boolean
     **/
    public function getConfirmData() {
        if (!isset($this->data['callback_data'])) {
            return '';
        }
        return $this->data['callback_data'];
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
     * @param array $data
     * @return self
     **/
    public static function parse($data) {
        if (isset($data['data']['params'])) {
            foreach ($data['data']['params'] as $param) {
                if ($param['key'] == 'user_profile') {
                    $arrUserProfile = json_decode($param['value'], true);
                }
            }
        }
        $daQueryInfoResults = empty($data['data']['da_query_info']) ? [] : $data['data']['da_query_info'];

        //da_query_info
        $daQueryInfo = [];
        if ($data['data']['da_query_info']) {
            foreach ($data['data']['da_query_info'] as $info) {
                $daQueryInfo[$info['type']] = $info['result_list'][0];
            }
        }
        if ($data['params']['backend_service_info']) {
            $backendServiceInfo = $data['params']['backend_service_info'];
        }
        if (!$backendServiceInfo) {
            $backendServiceInfo = [];
        }
        
        //session的数据结构参考us.idl里的BackendResponse
        $session = [];
        //for bot rename 2017-04-05 14:56:59
        if ($data['bot_sessions'] && isset($data['bot_sessions'][0]['list_sessions_str'][0])){
            $session = json_decode($data['bot_sessions'][0]['list_sessions_str'][0], true);
        }

        //\Logger::debug("us request parse time use:".(microtime(1) - $__time));

        //device_data
        $deviceData = [];
        if($data['msg']['device_data']) {
            $deviceData = json_decode($data['msg']['device_data'], true);
        }

        return new self([
            'user_profile' => $arrUserProfile,
            'session' => new Session($session),
            'daQueryInfoResults' => $daQueryInfoResults,
            'daQueryInfo' => $daQueryInfo,
            'backendServiceInfo' => $backendServiceInfo,
            'deviceData' => $deviceData,
            'data' => $data,
        ]);
    }

    /**
     * @param array
     * @return null
     **/
    private function __construct($data) {
        $this->arrUserProfile = $data['user_profile'];
        $this->session = $data['session'];
        $this->daQueryInfoResults = $data['daQueryInfoResults'];
        $this->daQueryInfo = $data['daQueryInfo'];
        $this->backendServiceInfo = $data['backendServiceInfo'];
        $this->deviceData = $data['deviceData'];
        $this->data = $data['data'];
    }
}


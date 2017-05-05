<?php
namespace Baidu\Duer\Botsdk;

class Request {
    private $session;
    private $msg;
    private $arrUserProfile;
    private $daQueryInfo;
    private $parsedText = '';
    private $daQueryInfoResults;
    private $data;
    private $backendServiceInfo;
    private $dumiDecisionInfo;

    public function getUserProfile() {
        return $this->arrUserProfile;
    }

    public function getSession() {
        return $this->session;
    }

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
    
    public function getDeviceData(){
        if(isset($this->data["msg"]['device_data'])){
            return json_decode($this->data["msg"]["device_data"],true);
        }
        return [];
    }
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
    public function getSearchBoxVer() {
        if (empty($this->data['msg']['searchbox_ver'])) {
            return '';
        }
        return $this->data['msg']['searchbox_ver'];
    }
    public function getOperationSystem() {
        if (empty($this->data['msg']['operation_system'])) {
            return '';
        }
        return $this->data['msg']['operation_system'];
    }
    
    public function getUserInfo() {
        return $this->data['user_info'];
    }
    public function getDaQueryInfo() {
        return $this->daQueryInfo;
    }
    
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
    

    public function getRequestType() {
        return $this->data['request_type'];
    }
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
    public function getDumiId() {
        return $this->data['dumi_id'];
    }
    public function getQuery() {
        return $this->data['query'];
    }
    public function getQueryType() {
        if (empty($this->data['msg']['query_type'])) {
            return '';
        }
        return $this->data['msg']['query_type'];
    }
    public function getChannelFrom()
    {
        if (empty($this->data['msg']['channel_from'])) {
            return '';
        }
        return $this->data['msg']['channel_from'];
    }
    public function getLocation() {
        return $this->data['location'];
    }
    public function getLastLocation() {
        return $this->data['last_location'];
    }
    public function getClientFrom() {
        return $this->data['params']['client_from'];
    }
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

    public function hasLogin() {
        return !empty($this->data['baiduid']);
    }
    public function isLaunchRequest(){
        return !!$this->data['launch'];
    }
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
    public function getConfirm() {
        if (!isset($this->data['confirm'])) {
            return 0;
        }
        return intval($this->data['confirm']);
    }
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

    public static function parse($data) {
        $parsed_text = '';
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

        return new self([
            'user_profile' => $arrUserProfile,
            'session' => new Session($session),
            'daQueryInfoResults' => $daQueryInfoResults,
            'daQueryInfo' => $daQueryInfo,
            'backendServiceInfo' => $backendServiceInfo,
            'dumiDecisionInfo' => $dumiDecisionInfo,
            'data' => $data,
            'parsed_text' => $parsed_text,
        ]);
    }
    private function __construct($data) {
        $this->arrUserProfile = $data['user_profile'];
        $this->session = $data['session'];
        $this->daQueryInfoResults = $data['daQueryInfoResults'];
        $this->daQueryInfo = $data['daQueryInfo'];
        $this->backendServiceInfo = $data['backendServiceInfo'];
        $this->dumiDecisionInfo = $data['dumiDecisionInfo'];
        $this->data = $data['data'];
        $this->parsedText = $data['parsed_text'];
    }

}

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
 * @desc Bot-sdk基类。使用都需要继承这个类
 * @author yuanpeng01@baidu.com
 **/
namespace Baidu\Duer\Botsdk;

use \Baidu\Apm\BotMonitorsdk;
use \Curl\Curl;

abstract class Bot{
	const DEFAULT_EVENT_NAME = '__default__';

    private $handler = [];
    private $intercept = [];
    private $event = [];

    public $certificate;

    /**
     * DuerOS对Bot的请求。instance of Request
     **/
    public $request;

    /**
     * Bot返回给DuerOS的结果。instance of Response
     **/
    public $response;

    /**
     * DuerOS提供的session。instance of Session
     * 短时记忆能力
     **/
    public $session;

    /**
     * 度秘NLU对query解析的结果。instance of Nlu
     **/
    public $nlu;

    /**
     * 统计Bot运行中产生的技能数据。instance of BotMonitor
     **/
    public $botMonitor;
    
    /**
     * 构造函数
     * @example
     * <pre>
     * // 子类调用
     * parent::__construct($requestBody, $privateKey);
     * // 或者
     * parent::__construct($privateKey);
     * </pre>
     *
     * @param array $postData us对bot的数据。默认可以为空，sdk自行获取
     * @param string $privateKey 私钥内容 
     * @return null
     **/
    public function __construct($postData=[], $privateKey = '') {
        /**
         * 如果第一个参数是字符串，认为是privateKey
         */
        if ($postData && is_string($postData)) {
            $privateKey = $postData;
            $postData = false;
        }

        if(!$postData){
            $rawInput = file_get_contents("php://input");
            $rawInput = str_replace("", "", $rawInput);
            $postData = json_decode($rawInput, true);
            //Logger::debug($this->getSourceType() . " raw input" . $raw_input);
        }
        $this->botMonitor = new \Baidu\Apm\BotMonitorsdk\BotMonitor($postData);
        $this->request = new Request($postData);
        $this->certificate = new Certificate($privateKey);


        $this->session = $this->request->getSession();

        $this->nlu = $this->request->getNlu();
        $this->response = new Response($this->request, $this->session, $this->nlu);
        $this->addDefaultEventListener('defaultEvent');
    }

    /**
     * @desc 添加对LaunchRequest 的处理函数
     * @example
     * <pre>
     * $this->addLaunchHandler(function(){
     *     return [
     *         'outputSpeech' => '欢迎使用'
     *     ];
     * });
     * </pre>
     *
     * @param function $func 处理函数。返回值作为response给DuerOS
     * @return null
     **/
    protected function addLaunchHandler($func) {
        return $this->addHandler('LaunchRequest', $func);    
    }

    /**
     * @desc 添加对SessionEndedRequest 的处理函数
     * @example
     * <pre>
     * $this->addSessionEndedHandler(function(){
     *      // TODO: clear status
     * });
     * </pre>
     *
     * @param function $func 处理函数。DuerOS不会使用该返回值
     * @return null
     **/
    protected function addSessionEndedHandler($func) {
        return $this->addHandler('SessionEndedRequest', $func);    
    }

    /**
     * @desc 添加对特定意图的处理函数
     * @example
     * <pre>
     * $this->addIntentHandler('intentName', function(){
     *     return [
     *         'outputSpeech' => '你的意图，我已经处理好了'
     *     ];
     * });
     * </pre>
     *
     * @param string $intentName  意图名称
     * @param function $func 处理函数。返回值作为response给DuerOS
     * @return null
     **/
    protected function addIntentHandler($intentName, $func) {
        return $this->addHandler('#'.$intentName, $func);    
    }

    /**
     * @desc 条件处理。顺序相关，优先匹配先添加的条件：
     *       1、如果满足，则执行，有返回值则停止
     *       2、满足条件，执行回调返回null，继续寻找下一个满足的条件
     * @param string $mix 条件，比如意图以'#'开头'#intentName'；或者是'LaunchRequest'、'SessionEndedRequest'
     * @param function $func 处理函数，满足$mix条件后执行该函数
     * @return null
     **/
    protected function addHandler($mix, $func=null){
        if(is_string($mix) && $func) {
            $arr = [];
            $arr[] = [$mix => $func]; 
            $mix = $arr;
        }

        if(!is_array($mix)) {
            return; 
        }
        foreach($mix as $item){
            foreach($item as $k => $v) {
                if(!$k || !$v) {
                    continue; 
                }

                $this->handler[] = [
                    'rule' => $k,
                    'func' => $v,
                ];
            }
        }
    }

    /**
     * @desc  拦截器
     *        1、在event处理、条件处理之前执行Intercept.preprocess，返回非null，终止后续执行。将返回值返回
     *        1、在event处理、条件处理之之后执行Intercept.postprocess
     *
     * @param Intercept $intercept
     * @return null
     **/
    protected function addIntercept(Intercept $intercept){
        $this->intercept[] = $intercept;
    }

    /**
     * @desc 绑定一个事件的处理回调。
     * @link http://developer.dueros.baidu.com/doc/dueros-conversational-service/device-interface/audio-player_markdown 具体事件参考
     *
     * @example
     * <pre>
     * $this->addEventListener('AudioPlayer.PlaybackStarted', function($event){
     *     return [
     *         'outputSpeech' => '事件处理好啦',
     *     ];
     * });
     * </pre>
     *
     * @param string  $event 绑定的事件名称，比如AudioPlayer.PlaybackStarted
     * @param function $func 处理函数，传入参数为事件的request，返回值做完response给DuerOS
     * @return null
     **/
    protected function addEventListener($event, $func){
        if($event && $func) {
            $this->event[$event] = $func;
        }
    }

	/**
     * @desc 默认兜底事件的处理回调。
     * @param function $func 处理函数，传入参数为事件的request，返回值做完response给DuerOS
     * @return null
     **/
    protected function addDefaultEventListener($func){
        if($func) {
            $this->event[self::DEFAULT_EVENT_NAME] = $func;
        }
    }
    /**
     * @desc 快捷方法。获取第一个intent的名字
     *
     * @param null
     * @return string
     **/
    public function getIntentName(){
        if($this->nlu){
            return $this->nlu->getIntentName();
        }
    }

    /**
     * @desc 快捷方法。获取session某个字段，与Session的getData功能相同
     * @param string $field 属性的key
     * @param string $default 如果该字段为空，使用$default返回
     * @return string
     **/
    public function getSessionAttribute($field=null, $default=null){
        return $this->session->getData($field, $default);
    }

    /**
     * @desc 快捷方法。设置session某个字段，与Session的setData功能相同。
     *       $field = 'a.b.c' 表示设置session['a']['b']['c'] 的值
     * @param string $field 属性的key
     * @param string $value 对应的值
     * @param string $default 如果$value为空，使用$default
     * @return bool
     **/
    public function setSessionAttribute($field, $value, $default=null){
        return $this->session->setData($field, $value, $default); 
    }

    /**
     * @desc 快捷方法。清空session，与Session的clear相同
     * @param null
     * @return null
     **/
    public function clearSessionAttribute(){
        return $this->session->clear(); 
    }

    /**
     * @desc 快捷方法。获取一个槽位的值，与Nlu中的getSlot相同
     * @param string $field 槽位名
     * @param integer $index  第几个intent，默认第一个
     * @return string
     **/
    public function getSlot($field, $index = 0){
        if($this->nlu){
            return $this->nlu->getSlot($field, $index);
        }
    }

    /**
     * @desc 快捷方法。设置一个槽位的值，与Nlu中的setSlot相同
     * @param string $field 槽位名
     * @param string $value 槽位的值
     * @param integer $index  第几个intent，默认第一个
     * @return string
     **/
    public function setSlot($field, $value, $index = 0){
        if($this->nlu){
            return $this->nlu->setSlot($field, $value, $index); 
        }
    }

    /**
     * @desc 告诉DuerOS，在多轮对话中，等待用户的回答。
     *       注意：如果有设置Nlu的ask，SDK自动告诉DuerOS，无须调用
     * @param null
     * @return null
     **/
    public function waitAnswer(){
        //should_end_session 
        $this->response->setShouldEndSession(false);
    }

    /**
     * @desc 告诉DuerOS，需要结束对话
     *
     * @param null
     * @return null
     **/
    public function endDialog(){
        $this->response->setShouldEndSession(true);
    }

    /**
     * @desc 事件路由添加后，需要执行此函数，对添加的条件、事件进行判断。
     *       将第一个return 非null的结果作为此次的response
     *
     * @param boolean $build  如果为false：不进行response封装，直接返回handler的result
     * @return array|string  封装后的结果为json string
     **/
    public function run($build=true){
        // 验证请求签名
        if(!$this->certificate->verifyRequest()) {
            return $this->response->illegalRequest();     
        }

        //handler event
        $eventHandler = $this->getRegisterEventHandler();

        //check domain
        if($this->request->getType() == 'IntentRequset' && !$this->nlu && !$eventHandler) {
            return $this->response->defaultResult(); 
        }

        //intercept beforeHandler
        $ret = [];
        foreach($this->intercept as $intercept) {
            $this->botMonitor->setPreEventStart();
            $ret = $intercept->preprocess($this);
            $this->botMonitor->setPreEventEnd();
            if($ret) {
                break; 
            }
        }

        if(!$ret) {
            //event process
            if($eventHandler) {
                $this->botMonitor->setDeviceEventStart();
                $event = $this->request->getEventData();
                $ret = $this->callFunc($eventHandler, $event);
                $this->botMonitor->setDeviceEventEnd();
            }else{
                $this->botMonitor->setEventStart();
                $ret = $this->dispatch();
                $this->botMonitor->setEventEnd();
            }
        }

        //intercept afterHandler
        foreach($this->intercept as $intercept) {
            $this->botMonitor->setPostEventStart();
            $ret = $intercept->postprocess($this, $ret);
            $this->botMonitor->setPostEventEnd();
        }

        $res = $this->response->build($ret);
        $this->botMonitor->setResponseData($res);
        $this->botMonitor->uploadData();

        if(!$build) {
            return $ret; 
        }
        return $res;
    }

    /**
     * @param null
     * @return array
     **/
    protected function dispatch(){
        if(!$this->handler) {
            return; 
        }

        foreach($this->handler as $item) {
            if($this->checkHandler($item['rule'])) {
                $ret = $this->callFunc($item['func']);
                
                if($ret) {
                    return $ret;
                }
            }
        }
    }

    /**
     * @param null
     * @return function
     **/
    private function getRegisterEventHandler() {
        $eventData = $this->request->getEventData();
        if($eventData['type']) {
            $key = $eventData['type'];
            if(isset($this->event[$key])) {
                return $this->event[$key];
            }else if(isset($this->event[self::DEFAULT_EVENT_NAME])){
				return $this->event[self::DEFAULT_EVENT_NAME];
			}
        }
    }

    /**
     * @param function $func
     * @param mixed  $arg
     * @return mixed
     **/
    private function callFunc($func, $arg=null){
        $ret;
        if(is_string($func)){
            $ret = call_user_func([$this, $func], [$arg]);
        }else{
            $ret = $func($arg); 
        }

        return $ret;
    }

    /**
     * @param string $rule
     * @return array
     * [
     *     [
     *         'type' => 'str',
     *         'value' => 'babab\'\"ab session slot #gagga isset > gag',
     *     ],
     *     [
     *         'type' => 'no_str',
     *         'value' => '#intent',
     *     ],
     * ]
     **/
    private function getToken($rule){
        $token = [];
        return $this->_getToken($token, $rule);
    }

    /**
     * @param &$token
     * @param $rule
     * @return array $token
     **/
    private function _getToken(&$token, $rule) {
        if($rule === "" || $rule === null) {
            return $token; 
        }

        $rgCom = '/[^"\']*/';
        preg_match($rgCom, $rule, $m);
        $token[] = [
            "type" => "no_str",
            "value" => $m[0],
        ];

        $last = substr($rule, mb_strlen($m[0]));
        if($last !== "" || $last !== null){
            for($i=1;$i<mb_strlen($last);$i++){
                $c = $last[$i];
                if($c == "\\"){
                    ++$i;
                    continue;
                }

                if($c == $last[0]){
                    $s = substr($last, 0, $i + 1);
                    $last = substr($last, mb_strlen($s));
                    $token[] = [
                        "type" => "str",
                        "value" => $s,
                    ];

                    break;
                }
            }
        }

        if($last){
            return $this->_getToken($token, $last);
        }

        return $token;
    }

    /**
     * @param string $handler
     * @return boolean
     **/
    private function checkHandler($handler){
		$rg = [
            'intent' => '/#([\w\.\d_]+)/',
            'requestType' => '/^(LaunchRequest|SessionEndedRequest)$/',
        ];
        if(preg_match($rg['requestType'], $handler) && $this->request->getType() == $handler){
            return true;
        }
        if(preg_match($rg['intent'], $handler) && '#' . $this->getIntentName() == $handler){
            return true;
        }
		if($handler === 'true' || $handler === true){
            return true;
        }
        return false;
    }

    /**
     * @param string $str
     * @return string
     **/
    private function tokenValue($str){
        if($str === '' || $str === null) {
            return ''; 
        }

        $rg = [
            'intent' => '/#([\w\.\d_]+)/',
            'session' => '/session\.([\w\.\d_]+)/',
            'slot' => '/slot\.([\w\d_]+)/',
            'requestType' => '/^(LaunchRequest|SessionEndedRequest)$/',
        ];

        $self = $this;
        foreach($rg as $k=>$r) {
            $str = preg_replace_callback($r, function($m) use($self, $k){
                if($k == 'intent'){
                    return json_encode($self->getIntentName() == $m[1]);
                }else if($k == 'session') {
                    return json_encode($self->getSessionAttribute($m[1]));
                }else if($k == 'slot') {
                    return json_encode($self->getSlot($m[1]));
                }else if($k == 'requestType') {
                    return json_encode($self->request->getType() == $m[1]);
                }
            }, $str); 
        }

        return $str;
    }

    /**
     * @desc
     **/
    public function declareEffect() {
        $this->response->setNeedDetermine();     
    }

    /**
     * @desc
     * @return bool
     **/
    public function effectConfirmed() {
        return $this->request->isDetermined();         
    }

    /**
     * @desc 通过控制expectSpeech来控制麦克风开
     * @param bool $expectSpeech 是否开启麦克风
     **/
    public function setExpectSpeech($expectSpeech){
        $this->response->setExpectSpeech($expectSpeech);
    }

    /**
     * @desc 表示本次返回的结果是兜底结果
     **/
    public function setFallBack(){
        $this->response->setFallBack();
    }
    
    /**
     * @desc 表示directives中指令顺序随机
     **/
    public function setAutoDirectivesArrangement(){
        $this->response->setAutoDirectivesArrangement();
    }

    /**
     * @desc 表示directives中指令保持相对顺序不变 (directives中指令可能会被过滤)
     **/
    public function setStrictDirectivesArrangement(){
        $this->response->setStrictDirectivesArrangement();
    }

    /**
     * @desc 判断设备是否支持Interface
     * @param string $interface
     * @return bool
     **/
    public function isSupportInterface($interface){
        $supportedInterfaces = $this->request->getSupportedInterfaces();
        if(is_array($supportedInterfaces) && array_key_exists($interface, $supportedInterfaces)){
            return true;
        }
        return false;
    }

    /**
     * @desc 判断设备是否支持Display
     * @return bool
     **/
    public function isSupportDisplay(){
        return $this->isSupportInterface('Display');
    } 

    /**
     * @desc 判断设备是否支持AudioPlayer
     * @return bool
     **/
    public function isSupportAudioPlayer(){
        return $this->isSupportInterface('AudioPlayer');
    } 

    /**
     * @desc 判断设备是否支持VideoPlayer
     * @return bool
     **/
    public function isSupportVideoPlayer(){
        return $this->isSupportInterface('VideoPlayer');
    }

    /**
     * 默认事件处理
     * @param array $event
     * @return array
     */
    public function defaultEvent($event = null){
        $this->waitAnswer();
        $this->setExpectSpeech(false);
    }

    /**
     * 技能所期待的用户回复，技能将该信息反馈给DuerOS，有助于DuerOS在语音识别以及识别纠错时向该信息提权。
     * 普通文本
     * @param string $text 普通文本内容类型回复表达的回复内容。
     */
    public function addExpectTextResponse($text){
        $this->response->addExpectTextResponse($text);    
    }

    /**
     * 技能所期待的用户回复，技能将该信息反馈给DuerOS，有助于DuerOS在语音识别以及识别纠错时向该信息提权。
     * 槽位类型
     * @param string $slot 槽位类型回复表达的槽位名称。
     */
    public function addExpectSlotResponse($slot){
        $this->response->addExpectTextResponse($slot);    
    }

    /**
     * 获取apiAccessToken
     * @return string
     */
    public function getApiAccessToken(){
        return $this->request->getApiAccessToken(); 
    }

    /**
     * 获取apiEndPoint
     * @return string
     */
    public function getApiEndPoint(){
        return $this->request->getApiEndPoint(); 
    }

    /**
     * curl封装
     * @param array $opts
     * @return mixed
     */
    public function curl($opts){
        $apiAccessToken = $this->getApiAccessToken();
        $apiEndPoint = $this->getApiEndPoint();
        $headers = array(
            'Authorization' => 'bearer ' . $apiAccessToken 
        );
        $defaultOpts = [
            'url' => '',
            'method' => 'get',
            'timeout' => 1,
            'uri' => $apiEndPoint,
            'path' => '',
            'data' => [],
            'headers' => $headers,
        ];
        $opts = array_merge($defaultOpts, $opts);

        $url = $opts['url'] ? : $opts['uri'] . $opts['path'];
        $curl = new Curl();
        $curl->setHeaders($opts['headers']);
        $curl->setRetry(1);
        $curl->setTimeout($opts['timeout']);
        if($opts['method'] == 'get'){
            $curl->get($url, $opts['data']);
        }else if($opts['method'] == 'post'){
            $curl->post($url, $opts['data']);
        }

        if ($curl->error) {
            return false;
        } else {
            return $curl->response;
        }
    }

    /**
     * 获取用户百度账号信息
     * 需要用户同意该权限才可以获取到信息，参考https://dueros.baidu.com/didp/doc/dueros-bot-platform/dbp-user-info/request-customer-information-api_markdown
     * @return mixed
     */
    public function getUserProfile(){
        $opts = array(
            'path' => '/saiya/v1/user/profile' 
        );
        return $this->curl($opts);
    }

    /**
     * 获取用户录音数据
     * 需要用户同意该权限才可以获取到信息，参考https://dueros.baidu.com/didp/doc/dueros-bot-platform/dbp-user-info/request-customer-information-api_markdown
     * @param string $audioToken RecordSpeechFinished事件中的audioToken
     * @return mixed
     */
    public function getRecordSpeech($audioToken){
        $opts = array(
            'path' => '/saiya/v1/user/record/speech',
            'data' => array(
                'audioToken' => $audioToken,
            ) 
        );
        return $this->curl($opts);
    }

    /**
     * 获取用户地理位置信息
     * 需要用户同意该权限才可以获取到信息，参考https://dueros.baidu.com/didp/doc/dueros-bot-platform/dbp-user-info/request-customer-information-api_markdown
     * @return mixed
     */
    public function getDeviceLocation(){
        $opts = array(
            'path' => '/saiya/v1/devices/location',
        );
        return $this->curl($opts);
    }

    /**
     * 调用智能家居打印机服务
     * 需要用户同意该权限才可以获取到信息，参考https://dueros.baidu.com/didp/doc/dueros-bot-platform/dbp-user-info/request-customer-information-api_markdown
     * @param array $data 
     * @return mixed
     */
    public function callSmarthomePrinter($data){
        $apiAccessToken = $this->getApiAccessToken();
        $headers = array(
            'Authorization' => 'bearer ' . $apiAccessToken,
            'Content-Type' => 'application/json' 
        );
        $opts = array(
            'path' => '/saiya/v1/smarthome/printer',
            'method' => 'post',
            'data' => $data,
            'headers' => $headers
        );
        return $this->curl($opts);
    }

    /**
     * 小度音响app通知接口
     * 需要用户同意该权限才可以获取到信息，参考https://dueros.baidu.com/didp/doc/dueros-bot-platform/dbp-user-info/request-customer-information-api_markdown
     * @param array $data 
     * @return mixed
     */
    public function sendMateappNotification($data){
        $apiAccessToken = $this->getApiAccessToken();
        $headers = array(
            'Authorization' => 'bearer ' . $apiAccessToken,
            'Content-Type' => 'application/json' 
        );
        $opts = array(
            'path' => '/saiya/v1/mateapp/notification',
            'method' => 'post',
            'data' => $data,
            'headers' => $headers
        );
        return $this->curl($opts);
    }

}

<?php
/**
 * Bot-sdk基类。使用都需要继承这个类
 * @author yuanpeng01@baidu.com
 **/
namespace Baidu\Duer\Botsdk;

abstract class Bot{

    private $handler = [];
    private $intercept = [];
    private $event = [];

    /**
     * 中控对Bot的请求
     **/
    public $request;

    /**
     * Bot返回给中控的结果
     **/
    public $response;

    /**
     * 中控提供的session。
     * 短时记忆能力
     **/
    public $session;

    /**
     * 度秘NLU对query解析的结果
     **/
    public $nlu;

    /**
     * 是否第三方自有解析
     **/
    private $nluSelf = false;
    
    /**
     * @param string $domain 关注的domain
     * @param array $postData us对bot的数据
     * @return null
     **/
    public function __construct($postData=[] ) {
        if(!$postData){
            $rawInput = file_get_contents("php://input");
            $rawInput = str_replace("", "", $rawInput);
            $postData = json_decode($rawInput, true);
            //Logger::debug($this->getSourceType() . " raw input" . $raw_input);
        }
        $this->request = new Request($postData);

        $this->session = $this->request->getSession();

        $this->nlu = $this->request->getNlu();
        $this->response = new Response($this->request, $this->session, $this->nlu);
    }


    /**
     * @desc 条件处理。顺序相关，优先匹配先添加的条件：
     *       1、如果满足，则执行，有返回值则停止
     *       2、满足条件，执行回调返回null，继续寻找下一个满足的条件
     * @param string|array $mix
     * @param function $func
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
     *        1、在event处理、条件处理之前执行Intercept.before，返回非null，终止后续执行。将返回值返回
     *        1、在event处理、条件处理之之后执行Intercept.after
     *
     * @param Intercept $intercept
     * @return null;
     **/
    protected function addIntercept(Intercept $intercept){
        $this->intercept[] = $intercept;
    }

    /**
     * @desc 有event，不执行handler
     * @param string  $event。namespace.name
     * @param function $func
     * @return null
     **/
    protected function addEventListener($event, $func){
        if($event && $func) {
            $this->event[$event] = $func;
        }
    }

    /**
     * @param null
     * @return string
     **/
    public function getIntent(){
        if($this->nlu){
            return $this->nlu->getIntent();
        }
    }

    /**
     * @param string $field
     * @param string $default
     * @return string
     **/
    public function getSession($field=null, $default=null){
        return $this->session->getData($field, $default);
    }

    /**
     * @param string $field
     * @param string $value
     * @param string $default
     **/
    public function setSession($field, $value, $default=null){
        return $this->session->setData($field, $value, $default); 
    }

    /**
     * @param null
     * @return null
     **/
    public function clearSession(){
        return $this->session->clear(); 
    }

    /**
     * @param string $field
     * @return string
     **/
    public function getSlot($field){
        if($this->nlu){
            return $this->nlu->getSlot($field);
        }
    }

    /**
     * @param string $field
     * @param string $value
     * @return string
     **/
    public function setSlot($field, $value){
        return $this->nlu->setSlot($field, $value); 
    }

    /**
     * @desc 副作用操作，向中控声明，接下来的操作是有副作用的
     * @param null
     * @return array
     * */
    public function declareEffect(){
        //TODO return a confirm message
        $this->response->setConfirm();
        return [
            'card' => new Card\Text('confirm us'),
        ]; 
    }

    /**
     * @desc 中控是否同意进行副作用的操作
     * @param null
     * @return boolean
     **/
    public function effectConfirmed(){
        return $this->request->getConfirm(); 
    }

    /**
     * @desc 告诉中控，在多轮对话中，等待用户的回答
     *       注意：如果有设置Nlu的ask，自动告诉中控，不用调用
     * @param null
     * @return null
     **/
    public function waitAnswer(){
        //should_end_session 
        $this->response->setShouldEndSession(false);
    }

    /**
     * @param boolean $build  false：不进行封装，直接返回handler的result
     * @return array|string  封装后的结果为json string
     **/
    public function run($build=true){
        //handler event
        $eventHandler = $this->getRegisterEventHandler();

        //check domain
        if($this->request->getType() == 'IntentRequset' && !$this->nlu && !$eventHandler && !$this->nluSelf) {
            return $this->response->defaultResult(); 
        }

        //intercept beforeHandler
        $ret = [];
        foreach($this->intercept as $intercept) {
            $ret = $intercept->before($this);
            if($ret) {
                break; 
            }
        }

        if(!$ret) {
            //event process
            if($eventHandler) {
                $event = $this->request->getDeviceData()['device_event'];
                $ret = $this->callFunc($eventHandler, $event); 
            }else{
                $ret = $this->dispatch();
            }
        }

        //intercept afterHandler
        foreach($this->intercept as $intercept) {
            $ret = $intercept->after($this, $ret);
        }

        if(!$build) {
            return $ret; 
        }
        return $this->response->build($ret);
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
        $deviceData = $this->request->getDeviceData();
        if($deviceData['device_event']) {
            $deviceEvent = $deviceData['device_event'];
            $key = implode('.', [$deviceEvent['header']['namespace'], $deviceEvent['header']['name']]);
            if($this->event[$key]) {
                return $this->event[$key];
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
     * @param null
     * @return null
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
        $token = $this->getToken($handler);
        if(!is_array($token)) {
            return false; 
        }

        $arr = []; 
        foreach($token as $t) {
            if($t['type'] == 'str') {
                $arr[] = $t['value']; 
            }else{
                $arr[] = $this->tokenValue($t['value']); 
            }
        }
        
        $str = implode('', $arr);
        //字符串中有$
        $str = str_replace('$', '\$', $str);
        //var_dump($str);
        $func = create_function('', 'return ' . implode('', $arr) . ';');
        return $func();
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
            'requestType' => '/^(LaunchRequest|sessionEndRequest)$/',
        ];

        $self = $this;
        foreach($rg as $k=>$r) {
            $str = preg_replace_callback($r, function($m) use($self, $k){
                if($k == 'intent'){
                    return json_encode($self->getIntent() == $m[1]);
                }else if($k == 'session') {
                    return json_encode($self->getSession($m[1]));
                }else if($k == 'slot') {
                    return json_encode($self->getSlot($m[1]));
                }else if($k == 'requestType') {
                    return json_encode($self->request->getType() == $m[1]);
                }
            }, $str); 
        }

        return $str;
    }
}

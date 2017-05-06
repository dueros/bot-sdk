<?php

namespace Baidu\Duer\Botsdk;

abstract class Bot{

    private $handler = [];
    private $intercept = [];
    private $event = [];

    public $request;
    public $response;
    public $session;
    public $nlu;
    
    public function __construct($domain, $postData=[] ) {
        if(!$postData){
            $rawInput = file_get_contents("php://input");
            $rawInput = str_replace("", "", $rawInput);
            $postData = json_decode($rawInput, true);
            //Logger::debug($this->getSourceType() . " raw input" . $raw_input);
        }
        $this->request = Request::parse($postData);

        $this->session = $this->request->getSession();
        $this->nlu = $this->request->getNlu($domain);
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
     **/
    protected function addEventListener($eventName, $func){
        $this->event[$eventName] = $func;
    }

    protected function getIntent(){
        return $this->nlu->getIntent();
    }

    protected function getSession($field=null, $default=null){
        return $this->session->getData($field, $default);
    }

    protected function setSession($field, $value, $default=null){
        return $this->session->setData($field, $value, $default); 
    }

    protected function clearSession(){
        return $this->session->clear(); 
    }

    protected function getSlot($field){
        return $this->nlu->getSlot($field);
    }

    protected function setSlot($field, $value){
        return $this->nlu->setSlot($field, $value); 
    }

    protected function getTxtView($text, $url=''){
        $view = [
            'type' => 'txt',
            'content' => $text,
        ]; 
        if($url) {
            $view['url']  = $url;
        }

        return $view;
    }

    /**
     * @desc 副作用操作，向中控声明，接下来的操作是有副作用的
     * */
    protected function declareEffect(){
        //TODO return a confirm message
        $this->response->setConfirm();
        return [
            'views' => [
                $this->getTxtView('confirm us') 
            ]
        ]; 
    }

    /**
     * @desc 中控是否同意进行副作用的操作
     **/
    protected function effectConfirmed(){
    
    }

    /**
     * @desc 告诉中控，在多轮对话中，等待用户的回答
     *       注意：如果有设置Nlu的ask，自动告诉中控，不用调用
     **/
    protected function waitAnswer(){
        //should_end_session 
    }

    public function run(){
        //handler event

        //check domain
        if(!$this->nlu) {
            return; 
        }

        //intercept beforeHandler
        $ret = [];
        foreach($this->intercept as $intercept) {
            $ret = $intercept->before($this);
            if($ret) {
                break; 
            }
        }

        //event process


        //
        if(!$ret) {
            $ret = $this->dispatch();
        }

        //intercept afterHandler
        foreach($this->intercept as $intercept) {
            $ret = $intercept->after($this, $ret);
        }

        return $this->response->build($ret);
    }

    protected function dispatch(){
        if(!$this->handler) {
            return; 
        }

        foreach($this->handler as $item) {
            if($this->checkHandler($item['rule'])) {
                if(is_string($item['func'])){
                    $ret = call_user_func([$this, $item['func']]);
                }else{
                    $ret = $item['func'](); 
                }
                if($ret) {
                    return $ret;
                }
            }
        }
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
        $func = create_function('', 'return ' . implode('', $arr) . ';');
        return $func();
    }

    private function tokenValue($str){
        if($str === '' || $str === null) {
            return ''; 
        }

        $rg = [
            'intent' => '/#([\w\.\d_]+)/',
            'session' => '/session\.([\w\.\d_]+)/',
            'slot' => '/slot\.([\w\d_]+)/',
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
                }
            }, $str); 
        }

        return $str;
    }
}

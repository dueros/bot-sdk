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
 * @desc 封装Bot对DuerOS的返回结果
 * @author yuanpeng01@baidu.com
 **/
namespace Baidu\Duer\Botsdk;

class Response{
    /**
     * Requset 实例。DuerOS的请求
     **/
    private $request;

    /**
     * Session
     **/
    private $session;

    /**
     * Nlu
     **/
    private $nlu;

    /**
     * 返回结果的标识。
     **/
    private $sourceType;

    /**
     * 多轮情况下，是否需要client停止对用户的等待输入
     **/
    private $shouldEndSession = true;

    private $needDetermine = false;

    private $expectSpeech;

    private $fallBack = false;

    private $expectResponse;

    private $directivesArrangement;

    /**
     * @param Request $request 请求对象
     * @param Session $session session对象
     * @param Nlu $nlu nlu对象
     * @return null
     **/
    public function __construct($request, $session, $nlu){
        $this->request = $request;
        $this->session = $session;
        $this->nlu = $nlu;
        $this->sourceType = $this->request->getBotId();
    }

    /**
     * 设置对话结束
     *
     * @param null
     * @return null
     **/
    public function setShouldEndSession($val){
        if($val === false) {
            $this->shouldEndSession = false; 
        }else if($val === true){
            $this->shouldEndSession = true; 
        }
    }


    /**
     * @desc 当没有结果时，返回默认值
     * @param null
     * @return null
     **/
    public function defaultResult(){
        return json_encode(['status'=>0, 'msg'=>null]);
    }

    /** 
     * @desc 构造response返回结果
     * @param array $data 数据
     * @example
     * <pre>
     * $data = [
     *    "card"=> $card // instanceof Card\BaseCard
     *    "directives"=> $directives  // array
     *    "outputSpeech"=> "string"
     *    "reprompt" => "string"
     *  ]
     * </pre>
     * @return string
     */
    public function build($data){
        if($this->nlu && $this->nlu->hasAsked()){
            $this->shouldEndSession = false;
        }
		
        $data['directives'] = isset($data['directives'])?$data['directives']:null;
        $data['card'] = isset($data['card'])?$data['card']:null;
        $data['outputSpeech'] = isset($data['outputSpeech'])?$data['outputSpeech']:null;
        $data['resource'] = isset($data['resource'])?$data['resource']:null;
        $data['reprompt'] = isset($data['reprompt'])?$data['reprompt']:null;

        $autoCompleteSpeech = true;
        if(isset($data['autoCompleteSpeech']) && is_bool($data['autoCompleteSpeech'])){
            $autoCompleteSpeech = $data['autoCompleteSpeech'];
        }

        $directives = $data['directives'] ? $data['directives'] : [];
        //directive to data
        $directives = array_values(array_filter(array_map(function($directive){
            if($directive instanceof Directive\BaseDirective) {
                $data = $directive->getData();
                if (isset($data['type']) && $data['type'] === 'DPL.ExecuteCommands') {
                    $token = $this->getTemplateToken();
                    if ($token) {
                        $data['token'] = $token;
                    }
                }
                return $data;
            } 
        }, $directives))); 

        if($this->nlu){
            $arr = $this->nlu->toDirective();
            if($arr) {
                $directives[] = $arr;
            }
        }
	
        if($autoCompleteSpeech && !$data['outputSpeech'] && $data['card'] && $data['card'] instanceof Card\TextCard) {
            $data['outputSpeech'] = $data['card']->getData('content');
        }
        $ret = [
            'version' => '2.0',
            'context' => $this->buildContext(), 
            'session' => $this->session->toResponse(),
            'response' => [
                'directives' => $directives,
                'shouldEndSession' => $this->shouldEndSession,
                'card' => $data['card']?$data['card']->getData():null,
                'resource' => $data['resource'],
                'outputSpeech' => $data['outputSpeech']?$this->formatSpeech($data['outputSpeech']):null,
                'reprompt' => $data['reprompt']?[
                    'outputSpeech' => $this->formatSpeech($data['reprompt']),
                ]:null
            ]
        ];

        if($this->needDetermine) {
            $ret['response']['needDetermine'] = $this->needDetermine;
        }

        if(isset($this->expectSpeech)) {
            $ret['response']['expectSpeech'] = $this->expectSpeech;
        }

        if($this->fallBack) {
            $ret['response']['fallBack'] = $this->fallBack;
        }

        if($this->directivesArrangement) {
            $ret['response']['directivesArrangement'] = $this->directivesArrangement;
        }

        $str=json_encode($ret, JSON_UNESCAPED_UNICODE);
        return $str;
    }

    /**
     * @desc 通过正则<speak>..</speak>，判断是纯文本还是ssml，生成对应的format
     * @param string|array $mix 输入的语句
     * @return array
     **/
    public function formatSpeech($mix){
        if($mix instanceof Extensions\TTSTemplate){
           return $mix->getData(); 
        }

        if(preg_match('/<speak>/', $mix)) {
            return [
                'type' => 'SSML',
                'ssml' => $mix,
            ]; 
        }else{
            return [
                'type' => 'PlainText',
                'text' => $mix,
            ];
        }
    }

    /**
     * @desc  非法请求
     * @return json
     **/
    public function illegalRequest() {
        return json_encode(['status'=>1, 'msg'=>'非法请求']);
    }

    /**
     * @desc 设置needDetermine为true
     **/
    public function setNeedDetermine(){
        $this->needDetermine = true; 
    }

    /**
     * @desc 通过控制expectSpeech来控制麦克风开关
 	 * @param bool $expectSpeech 麦克风是否开启
     **/
    public function setExpectSpeech($expectSpeech){
        if(is_bool($expectSpeech)){
            $this->expectSpeech = $expectSpeech; 
        }
    }

    /**
     * @desc 表示本次返回的结果是否为兜底结果
     **/
    public function setFallBack(){
        $this->fallBack = true; 
    }

    /**
     * @desc 表示directives中指令顺序随机
     **/
    public function setAutoDirectivesArrangement(){
        $this->directivesArrangement = 'AUTO'; 
    }

    /**
     * @desc 表示directives中指令保持相对顺序不变 (directives中指令可能会被过滤)
     **/
    public function setStrictDirectivesArrangement(){
        $this->directivesArrangement = 'STRICT'; 
    }
    
    /**
     * 技能所期待的用户回复，技能将该信息反馈给DuerOS，有助于DuerOS在语音识别以及识别纠错时向该信息提权。
     * 普通文本
     * @param string $text 普通文本内容类型回复表达的回复内容。
     */
    public function addExpectTextResponse($text){
        if($text && is_string($text)){
            $this->expectResponse[] = array(
                'type' => 'PlainText',
                'text' => $text 
            );
        }
    }

    /**
     * 技能所期待的用户回复，技能将该信息反馈给DuerOS，有助于DuerOS在语音识别以及识别纠错时向该信息提权。
     * 槽位类型
     * @param string $slot 槽位类型回复表达的槽位名称。
     */
    public function addExpectSlotResponse($slot){
        if($slot && is_string($slot)){
            $this->expectResponse[] = array(
                'type' => 'Slot',
                'slot' => $slot
            );
        }
    }

    /**
     * 构造context结果
     * @return array
     */
    public function buildContext(){
        $context = [];
        if($this->nlu && $this->nlu->toUpdateIntent()){
            $context['intent'] = $this->nlu->toUpdateIntent(); 
        }
        if($this->expectResponse){
            $context['expectResponse'] = $this->expectResponse;
        }
        if($this->nlu){
            $afterSearchScore = $this->nlu->getAfterSearchScore();
            if($afterSearchScore && is_double($afterSearchScore)){
                $context['afterSearchScore'] = $afterSearchScore;
            }
        }
        if(!$context){
            $context = (object)[]; 
        }
        return $context;
    }
    /**
     * 自动获取token
     * @return string
     */
    public function getTemplateToken(){
        $data = $this->request->getData();
        $token = '';
        if (isset($data['context']['Screen']['token'])) {
            $token =  $data['context']['Screen']['token'];
        }
        return $token;
    }
}

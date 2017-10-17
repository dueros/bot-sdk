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

        $directives = $data['directives'] ? $data['directives'] : [];
        //directive to data
        $directives = array_values(array_filter(array_map(function($directive){
            if($directive instanceof Directive\BaseDirective) {
                return $directive->getData();    
            } 
        }, $directives))); 

        if($this->nlu){
            $arr = $this->nlu->toDirective();
            if($arr) {
                $directives[] = $arr;
            }
        }

        if(!$data['outputSpeech'] && $data['card'] && $data['card'] instanceof Card\TextCard) {
            $data['outputSpeech'] = $data['card']->getData('content');
        }

        $ret = [
            'version' => '2.0',
            'context' => [
                'updateIntent' =>$this->nlu ? $this->nlu->toUpdateIntent() : null, 
            ],
            'session' => $this->session->toResponse(),
            'response' => [
                //'needDetermine' => $this->confirm ? true : false,
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

        
        $str=json_encode($ret, JSON_UNESCAPED_UNICODE);
        return $str;
    }

    /**
     * @desc 通过正则<speak>..</speak>，判断是纯文本还是ssml，生成对应的format
     * @param string|array $mix 输入的语句
     * @return array
     **/
    public function formatSpeech($mix){
        if(is_array($mix)) {
            return $mix; 
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
}

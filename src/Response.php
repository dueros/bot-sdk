<?php
/**
 * 封装Bot对DuerOS的返回结果
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
     * 对DuerOS的confirm标识。标识是否需要对DuerOSconfirm
     **/
    private $confirm;

    /**
     * 多轮情况下，是否需要client停止对用户的等待输入
     **/
    private $shouldEndSession = true;

    /**
     * @param Request $request
     * @param Session $session
     * @param Nlu $nlu
     * @return null
     **/
    public function __construct($request, $session, $nlu){
        $this->request = $request;
        $this->session = $session;
        $this->nlu = $nlu;
        $this->sourceType = $this->request->getBotName();
    }

    /**
     * @param null
     * @return null
     **/
    public function setConfirm(){
        $this->confirm = 1; 
    }

    /**
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
     * @param array $data
     * $data = [
     *    "card"=> $card // instanceof Card\Base
     *    "directives"=> $directive  TODO
     *    "outputSpeech"=> "string"
     *    "reprompt" => "string"
     *  ]
     *
     * @return string
     */
    public function build($data){
        if($this->nlu && $this->nlu->hasAsk()){
            $this->shouldEndSession = false;
        }

        $directives = $data['directives'] ? $data['directives'] : [];
        if($this->nlu){
            $arr = $this->nlu->toDirective();
            if($arr) {
                $directives[] = $arr;
            }
        }

        if(!$data['outputSpeech'] && $data['card'] && $data['card'] instanceof Card\Text) {
            $data['outputSpeech'] = $data['card']->getData('content');
        }

        $ret = [
            'version' => '2.0',
            'context' => [
                'updateIntent' =>$this->nlu ? $this->nlu->toUpdateIntent() : null, 
            ],
            'session' => $this->session->toResponse(),
            'response' => [
                'needDetermine' => $this->confirm ? true : false,
                'directives' => $directives,
                'shouldEndSession' => $this->shouldEndSession,
                'card' => $data['card']?$data['card']->getData():null,
                //'resource' => $data['resource'],
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
     * @param string|array $mix
     * @return array
     **/
    public function formatSpeech($mix){
        if(is_array($mix)) {
            return $mix; 
        }

        if(preg_match('/<speak>/', $mix)) {
            return [
                'type' => 'ssml',
                'ssml' => $mix,
            ]; 
        }

        return [
            'type' => 'text',
            'text' => $mix,
        ];
    }
}

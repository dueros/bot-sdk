<?php
/**
 * 登录拦截器
 * @author yuanpeng01@baidu.com
 **/
namespace Baidu\Duer\Botsdk\Plugins;

class LoginIntercept extends \Baidu\Duer\Botsdk\Intercept{

    /**
     * @param string $tip
     * @param integer $threshold
     * @return null
     **/
    public function __construct($tip="非常抱歉，你需要登录百度帐号") {
        $this->tip = $tip;
    }

    /**
     * @param Bot $bot
     * @return mixed
     **/
    public function before($bot){
        if(!$bot->request->getBduss()) {
            return [ 
                'result_list' => [
                    0 => [
                        'result_confidence' => 100,
                        'result_content' => [
                            'answer' => $this->tip,
                        ],
                        'result_type' => 'login',
                        'voice' => $this->tip,
                        'source_type' => $bot->request->getBotName(),
                    ],
                ]
            ];
        }
    }
}


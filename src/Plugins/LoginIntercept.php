<?php

namespace Baidu\Duer\Botsdk\Plugins;

class LoginIntercept extends \Baidu\Duer\Botsdk\Intercept{
    public function __construct($tip="非常抱歉，你需要登录百度帐号") {
        $this->tip = $tip;
    }

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


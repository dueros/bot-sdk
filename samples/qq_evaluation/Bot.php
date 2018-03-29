<?php
/**
 * Copyright (c) 2018 Baidu, Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the
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
 * @desc QQ_test class
 * @author joyce
 * @email zhangzhaojing@baidu.com
 *
 **/

require '../../../../vendor/autoload.php';

use \Baidu\Duer\Botsdk\Card\TextCard;
use \Baidu\Duer\Botsdk\Card\StandardCard;

class Bot extends \Baidu\Duer\Botsdk\Bot {
    //获得权限的appkey
    private static $appkey = "96e651aba46125748ab8850630609186";
    //欢迎页图片url
    private static $picUrl = "http://img.25pp.com/uploadfile/soft/images/2014/0226/20140226041227784.jpg";

    /**
    * 构造函数
    * @param null
    * @return null
    **/
    public function __construct($postData = []) {
        parent::__construct();
        $this->log = new \Baidu\Duer\Botsdk\Log([

            //日志存储路径
            'path' => 'log/',
            //日志打印最低输出级别
            'level' => \Baidu\Duer\Botsdk\Log::NOTICE,
        ]);

        //记录请求的query
        $this->log->setField('query', $this->request->getQuery());

        //添加对LaunchRequest 的处理函数
        $this->addLaunchHandler(function () {
            $card = new StandardCard();
            $card->setTitle('QQ测吉凶')
                ->setContent('欢迎使用‘QQ测吉凶’')
                ->setImage(self::$picUrl);

            $this->waitAnswer();
            return [
                'card' => $card,
                'outputSpeech' => '欢迎使用QQ测吉凶’',
            ];
        });

        //添加对SessionEndedRequest 的处理函数
        $this->addSessionEndedHandler(function () {
            $this->waitAnswer();
            return [
                'card' => new TextCard('感谢使用‘QQ测吉凶’'),
                'outputSpeech' => '感谢使用’',
            ];
        });

        //添加对特定意图的处理函数
        $this->addIntentHandler('qqEvaluation', function () {
            if (!$this->getSlot('qqNumber')) {
                $this->nlu->ask('qqNumber');
                $card = new TextCard('方便告诉我一下你的QQ号吗?');

                $this->waitAnswer();
                return [
                    'card' => $card,
                    'reprompt' => '方便告诉我一下你的QQ号吗?',
                    'resource' => [
                        'type' => 1,
                    ],
                ];
            } else {
                return $this->testQqFate();
            }
        });
    }

    /**
     * @desc 消息处理函数。
     * @param null
     * @return $data 获取的消息
     **/
    public function testQqFate() {
        $qq = $this->getSlot('qqNumber');

        //构造接口url
        $url = 'http://japi.juhe.cn/qqevaluate/qq'
            . '?key=' . self::$appkey
            . '&qq=' . $qq;

        $this->log->markStart('url_t');
        $res = file_get_contents($url);
        $this->log->markEnd('url_t');

        $data = json_decode($res, true);
        if ($data) {
            if ($data['error_code'] == '0') {
                $fate = $data['result']['data'];

                $card = new TextCard($qq."\r\n"
                    .'运势:'."\r\n".$fate['conclusion']."\r\n"
                    .'运势分析:'."\r\n".$fate['analysis']);
                
                return [
                    'card' => $card,
                    'outputSpeech' => $qq
                        .'运势:'.$fate['conclusion']
                        .'运势分析:'.$fate['analysis'],
                ];
            } else {
                echo $data['error_code'] . ":" . $data['reason'];
            }
        } else {
            return [
                $card = new TextCard('查无此号')
            ];
        }
    }
}

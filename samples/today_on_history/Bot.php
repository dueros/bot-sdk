<?php
/**
 * Copyright (c) 2018 Baidu, Inc. All Rights Reserved.                                                                            
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
 * @desc today_on_history class
 * @author joyce
 * @email:zhangzhaojing@baidu.com
 **/
 
require '../../../../vendor/autoload.php';
require '../simple_html_dom.php';

use \Baidu\Duer\Botsdk\Card\TextCard;
use \Baidu\Duer\Botsdk\Card\StandardCard;

class Bot extends \Baidu\Duer\Botsdk\Bot {
    //接口url
    private static $url = "http://www.todayonhistory.com/index.php?m=content&c=index&a=json_event&page=1&pagesize=40&";
    //欢迎页图片url
    private static $pUrl = "https://ss1.bdstatic.com/70cFuXSh_Q1YnxGkpoWK1HF6hhy/it/u=3892410230,3126045607&fm=27&gp=0.jpg";
    
    /**
    * 构造函数
    * @param null
    * @return null
    */
    public function __construct($postData = []) {
        parent::__construct();

        $this->log = new \Baidu\Duer\Botsdk\Log([
            //日志存储路径
            'path' => 'log/',
            'level' => \Baidu\Duer\Botsdk\Log::NOTICE,
        ]);

        //记录请求的query
        $this->log->setField('query', $this->request->getQuery());

        //添加对LaunchRequest 的处理函数
        $this->addLaunchHandler(function () {
            $card = new StandardCard();
            $card->setTitle('历史今日')
                ->setContent('欢迎使用‘历史上的今天’')
                ->setImage(self::$pUrl);

            $this->waitAnswer();
            return [
                'card' => $card,
                'reprompt' => '要说什么呢？',
                'outputSpeech' => '欢迎使用‘历史上的今天’',
            ];
        });

        //添加对SessionEndedRequest 的处理函数
        $this->addSessionEndedHandler(function () {
            return [
                'card' => new TextCard('感谢使用‘历史上的今天’'),
                'outputSpeech' => 'byebye’',
            ];
        });

        //添加对特定意图的处理函数
        $this->addIntentHandler('historyToday', function () {
            $this->log->setField('query', $this->request->getQuery());
            if (!$this->getSlot('currentDate')) {
                $this->nlu->ask('currentDate');
                $card = new TextCard('请问您要知道几月几日的事儿呢?');
                $card->addCueWords(['3月3日', '2.14']);

                $this->waitAnswer();
                return [
                    'card' => $card,
                    'reprompt' => '请问您要知道几月几日的事儿呢？',
                    'resource' => [
                        'type' => 1,
                    ],
                ];
            } else {
                return $this->getHistory();
            }
        });

        //多轮对话时对意图的识别
        $this->addIntentHandler('otherHistoryToday', function () {
            if (!$this->getSlot('currentDate')) {
                $this->nlu->ask('currentDate');
                $card = new TextCard('请问您要知道几月几日的事儿呢？');
                $card->addCueWords(['3月3日', '2.14']);

                $this->waitAnswer();
                return [
                    'card' => $card,
                    'reprompt' => '请问您要知道几月几日的事儿呢？',
                    'resource' => [
                        'type' => 1,
                    ],
                ];
            } else {
                return $this->getHistory();
            }
        });
    }

    /**
     * @desc 获取数据。条件匹配，随机返回：
     *       1.获取该日期相应的数据，有返回值则停止
     *       2.如果error_code为0，随机返回单一数据
     * @param null
     * @return string $data 当前日期的历史事例
     **/
    public function getHistory() {
        $currentDate = $this->getSlot('currentDate');

        //将日期2018-03-04提取出来
        $arrayDate = explode('-', $currentDate);
        $month = intval($arrayDate[1]);
        $day = intval($arrayDate[2]);

        //构造接口url
        $url = self::$url . 'month=' . $month . '&day=' . $day;
        $data = $this->getHtmlInfo($url);
        $data = iconv('gb2312', 'utf-8', $data);                                                                                    
        //将json解码为字符串
        $data = json_decode($data, true);
        
        if (!empty($data)) {
            $m = rand(0, count($data) - 1);
            $history = $data[$m];
            $picUrl = "http://www.todayonhistory.com" . $history['thumb'];
            $desc = $this->getHistoryDetail($history['url']);
            
            $card = new StandardCard();
            $card->setTitle('历史上的' . $history['solaryear'] . '年' . $month . '月' . $day . '日:' . $history['title'])
                ->setContent($desc)
                ->setImage($picUrl)
                ->setAnchor('https://www.baidu.com/s?wd=' . $history['title']);
            return [
                'card' => $card,
                'outputSpeech' => $desc,
            ];
        } else {
            return $card = new TextCard('历史上的今天没有发生事情！');
        }
    }

    /**
     * @desc 获取事件的详细信息
     * @param string $hisUrl 事件信息
     * @return string $desc 事件信息
     */
    public function getHistoryDetail($url) {
        $detail = file_get_html($url);
        $desc = "";
        foreach ($detail->find('div.body') as $e) {
            $desc = $e->plaintext;
        }

        //过滤html残余字符
        $desc = preg_replace('/&nbsp;/is', '', $desc);
        $desc = preg_replace('/&rdquo;/is', '', $desc);
        $desc = preg_replace('/&ldquo;/is', '', $desc);
        $desc = preg_replace('/&middot;/is', '', $desc);
        
        return $desc;
    }

    /**
     * @desc CET方法通过url请求html文档
     * @param string $url html地址
     * @return array $data 事件信息
     */
    public function getHtmlInfo($url,$timeout = 3, $headerAry = '') {
        $this->log->markStart('url_t');
        
        if(is_array($timeout)) {
            $headerAry = $timeout;
            $timeout = 3;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, false);
        
        //当url中用ip访问时，允许用host指定具体域名
        if ($headerAry != '') {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerAry);
        }

        $res = curl_exec($ch);
        if (!$res) {
            $this->log->setField("errorLog",curl_errno($ch));   
        }

        $this->log->markEnd('url_t');
        return $res;
    }
}

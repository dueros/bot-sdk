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
 * @desc 这是一个自己完成NLU解析，开发的bot例子
 * 将多轮对话的状态存储到session中
 * 第一轮，如果用户query是hello，进入服务；回复hello
 * 第二轮，进入了这个bot的多轮，处理返回，记录当前的状态到session
 * 第三轮，结束对话，提示用户可以say hello 开始对话
 **/

require '../../../../../vendor/autoload.php';

use \Baidu\Duer\Botsdk\Card\TextCard;

class Bot extends Baidu\Duer\Botsdk\Bot{
    /**
     * @param null
     * @return null
     **/
    public function __construct($postData = []) {
        parent::__construct($postData);

        $this->log = new Baidu\Duer\Botsdk\Log([
            //日志存储路径
            'path' => 'log/',
            //日志打印最低输出级别
            'level' => Baidu\Duer\Botsdk\Log::NOTICE,
        ]);

        //test fatal log，你可以这样来输出一个fatal日志
        //$this->log->fatal("this is a fatal log");

        //log 一个字段
        $this->log->setField('query', $this->request->getQuery());
        $this->log->setField('session.status', $this->getSessionAttribute('status'));
        //你可以这样来添加一个插件
        //$this->addIntercept(new Baidu\Duer\Botsdk\Plugins\DuerSessionIntercept());

        $this->addHandler('true', function(){
            if($this->getSessionAttribute('status') == 1) {
                $this->setSessionAttribute('status', 2);

                $card = new TextCard('这是第二轮对话的回复');
                $this->waitAnswer();
                return [
                    'card' => $card 
                ];
            }

            if($this->getSessionAttribute('status') == 2) {
                $this->setSessionAttribute('status', 0); 

                $card = new TextCard('对话要结束啦，继续说"hello"开始');
                return [
                    'card' => $card 
                ];     
            }

            if($this->request->getQuery() !== 'hello') {
                return; 
            }

            $this->setSessionAttribute('status', 1);
            $card = new TextCard('hello');
            $this->waitAnswer();
            return [
                'card' => $card 
            ];
        });


    }
}

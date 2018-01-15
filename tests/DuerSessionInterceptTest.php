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
 **/

require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;

/**
 * @desc DuerSessionInterceptTestBot继承Baidu\Duer\Botsdk\Bot
 */
class DuerSessionInterceptTestBot extends Baidu\Duer\Botsdk\Bot{

    /**
     * @param array $postData us对bot的数据。默认可以为空，sdk自行获取
     * @return null
     **/
    public function __construct($postData = []) {
        parent::__construct($postData);
        $this->setSlot('da_system_not_understand', 1);
        $this->setSlot('bot_not_understand', 1);
        $this->addIntercept(new \Baidu\Duer\Botsdk\Plugins\DuerSessionIntercept());	
    }

}

/**
 * @desc DuerSessionIntercept类的测试类
 */
class DuerSessionInterceptTest extends PHPUnit_Framework_TestCase{

    /**
     * @before
     */
    public function setupSomeFixtures()
    {
        $data = json_decode(file_get_contents(dirname(__FILE__).'/json/intent_request.json'), true);
        $this->bot = new DuerSessionInterceptTestBot($data);
    }	

    /**
     * @desc 用于测试addIntercept方法
     */
    function testAddIntercept(){
        $ret = $this->bot->run();
        $rt = '{"version":"2.0","context":{"intent":{"name":"intentName","score":100,"confirmationStatus":"NONE","slots":{"city":{"name":"city","value":"北京","score":0,"confirmationStatus":"NONE"},"da_system_not_understand":{"name":"da_system_not_understand","value":1},"bot_not_understand":{"name":"bot_not_understand","value":1}}}},"session":{"attributes":{}},"response":{"directives":[],"shouldEndSession":true,"card":{"type":"txt","content":"非常抱歉，不明白你说的意思，已经取消了本次服务"},"resource":null,"outputSpeech":{"type":"PlainText","text":"非常抱歉，不明白你说的意思，已经取消了本次服务"},"reprompt":null}}';
        $this->assertEquals($ret, $rt);	
    }

}

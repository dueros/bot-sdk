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
 * @desc Bot类继承Baidu\Duer\Botsdk\Bot
 **/
class Bot extends Baidu\Duer\Botsdk\Bot{

    /**
     * @param array $postData us对bot的数据。默认可以为空
     * @return null
     **/
    public function __construct($postData = []) {
        parent::__construct($postData);
        $this->addHandler('#intentName', 'intentNameFunc');
    }

    /**
     * @return array
     **/	
    public function intentNameFunc(){
        $card = new \Baidu\Duer\Botsdk\Card\TextCard("测试服务");
        return [
            'card' => $card,
            'outputSpeech' => '测试服务，欢迎光临',
        ];
    }
}

/**
 * @desc BotTest类用于测试Bot类
 */
class BotTest extends PHPUnit_Framework_TestCase{

    /**
     * @before
     */
    public function setupSomeFixtures()
    {
        $data = json_decode(file_get_contents(dirname(__FILE__).'/../json/intent_request.json'), true);
        $this->bot = new Bot($data);
    }	

    /**
     * @desc 用于测试getIntentName方法
     */
    function testGetIntentName(){
        $this->assertEquals($this->bot->getIntentName(), 'intentName');
    }

    /**
     * @desc 用于测试getSessionAttribute方法
     */
    function testGetSessionAttribute(){
        $this->bot->setSessionAttribute('status', '1');
        $this->assertEquals($this->bot->getSessionAttribute('status'), '1');
    }

    /**
     * @desc 用于测试setSessionAttribute方法
     */
    function testSetSessionAttribute(){
        $this->bot->setSessionAttribute('status', '1');
        $this->assertEquals($this->bot->getSessionAttribute('status'), '1');
    }

    /**
     * @desc 用于测试clearSessionAttribute方法
     */
    function testClearSessionAttribute(){
        $this->bot->setSessionAttribute('status', '1');
        $this->bot->clearSessionAttribute();
        $this->assertNull($this->bot->getSessionAttribute('status'));
    }

    /**
     * @desc 用于测试getSlot方法
     */
    function testGetSlot(){
        $this->assertEquals($this->bot->getSlot('city'), '北京');
    }

    /**
     * @desc 用于测试setSlot方法
     */
    function testSetSlot(){
        $this->bot->setSlot('monthsalary', 1212);	
        $this->assertEquals($this->bot->getSlot('monthsalary'), 1212);
    }

    /**
     * @desc 用于测试waitAnswer方法
     */
    function testWaitAnswer(){
        $this->bot->waitAnswer();	
        $ret = [];
        $json = $this->bot->response->build($ret);
        $shouldEndSession = json_decode($json, true)['response']['shouldEndSession'];
        $this->assertFalse($shouldEndSession);
    }

    /**
     * @desc 用于测试endDialog方法
     */
    function testEndDialog(){
        $this->bot->endDialog();
        $ret = [];
        $json = $this->bot->response->build($ret);
        $shouldEndSession = json_decode($json, true)['response']['shouldEndSession'];
        $this->assertTrue($shouldEndSession);
    }

    /**
     * @desc 用于测试run方法
     */
    function testRun(){
        $ret = $this->bot->run();
        $rt = '{"version":"2.0","context":{"intent":{"name":"intentName","score":100,"confirmationStatus":"NONE","slots":{"city":{"name":"city","value":"北京","score":0,"confirmationStatus":"NONE"}}}},"session":{"attributes":{}},"response":{"directives":[],"shouldEndSession":true,"card":{"type":"txt","content":"测试服务"},"resource":null,"outputSpeech":{"type":"PlainText","text":"测试服务，欢迎光临"},"reprompt":null}}';
        $this->assertEquals($ret, $rt);
    }

    /**
     * @desc 用于测试setExpectSpeech方法
     */
    function testSetExpectSpeech(){
        $this->bot->setExpectSpeech(false);
        $ret = $this->bot->run();
        $rt ='{"version":"2.0","context":{"intent":{"name":"intentName","score":100,"confirmationStatus":"NONE","slots":{"city":{"name":"city","value":"北京","score":0,"confirmationStatus":"NONE"}}}},"session":{"attributes":{}},"response":{"directives":[],"shouldEndSession":true,"card":{"type":"txt","content":"测试服务"},"resource":null,"outputSpeech":{"type":"PlainText","text":"测试服务，欢迎光临"},"reprompt":null,"expectSpeech":false}}';
        $this->assertEquals($ret, $rt);

        $this->bot->setExpectSpeech(true);
        $ret = $this->bot->run();
        $rt ='{"version":"2.0","context":{"intent":{"name":"intentName","score":100,"confirmationStatus":"NONE","slots":{"city":{"name":"city","value":"北京","score":0,"confirmationStatus":"NONE"}}}},"session":{"attributes":{}},"response":{"directives":[],"shouldEndSession":true,"card":{"type":"txt","content":"测试服务"},"resource":null,"outputSpeech":{"type":"PlainText","text":"测试服务，欢迎光临"},"reprompt":null,"expectSpeech":true}}';
        $this->assertEquals($ret, $rt);
    }

    /**
     * @desc 用于测试setFallBack方法
     */
    function testSetFallBack(){
        $this->bot->setFallBack();
        $ret = $this->bot->run();
        $rt = '{"version":"2.0","context":{"intent":{"name":"intentName","score":100,"confirmationStatus":"NONE","slots":{"city":{"name":"city","value":"北京","score":0,"confirmationStatus":"NONE"}}}},"session":{"attributes":{}},"response":{"directives":[],"shouldEndSession":true,"card":{"type":"txt","content":"测试服务"},"resource":null,"outputSpeech":{"type":"PlainText","text":"测试服务，欢迎光临"},"reprompt":null,"fallBack":true}}';
        $this->assertEquals($ret, $rt);
    }

    /**
     * @desc 用于测试isSupportDisplay方法
     */
    function testIsSupportDisplay(){
        $data = json_decode(file_get_contents(dirname(__FILE__).'/../json/intent_request1.json'), true);
        $bot = new Bot($data);
        $ret = $bot->isSupportDisplay();
        $this->assertTrue($ret);
    }

    /**
     * @desc 用于测试isSupportDisplay方法
     */
    function testIsSupportAudioPlayer(){
        $data = json_decode(file_get_contents(dirname(__FILE__).'/../json/intent_request1.json'), true);
        $bot = new Bot($data);
        $ret = $bot->isSupportAudioPlayer();
        $this->assertTrue($ret);
    }

    /**
     * @desc 用于测试isSupportDisplay方法
     */
    function testIsSupportVideoPlayer(){
        $data = json_decode(file_get_contents(dirname(__FILE__).'/../json/intent_request1.json'), true);
        $bot = new Bot($data);
        $ret = $bot->isSupportVideoPlayer();
        $this->assertTrue($ret);
    }


}

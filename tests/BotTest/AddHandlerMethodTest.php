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
 * @desc 用于测试addHandler的Bot类
 **/	
class AddHandlerMethodTestBot extends Baidu\Duer\Botsdk\Bot{
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
 * @desc addHandler测试类
 **/	
class AddHandlerMethodTest extends PHPUnit_Framework_TestCase{

    /**
     * @before
     */
    public function setupSomeFixtures()
    {
        $data = json_decode(file_get_contents(dirname(__FILE__).'/../json/intent_request.json'), true);
        $this->bot = new AddHandlerMethodTestBot($data);
    }	

    /**
     * @desc addHandler测试方法
     **/	
    function testAddHandler(){
        $ret = $this->bot->run();
        $rt = '{"version":"2.0","context":{"intent":{"name":"intentName","score":100,"confirmationStatus":"NONE","slots":{"city":{"name":"city","value":"北京","score":0,"confirmationStatus":"NONE"}}}},"session":{"attributes":{}},"response":{"directives":[],"shouldEndSession":true,"card":{"type":"txt","content":"测试服务"},"resource":null,"outputSpeech":{"type":"PlainText","text":"测试服务，欢迎光临"},"reprompt":null}}'; 
        $this->assertEquals($ret, $rt);	
    }

}

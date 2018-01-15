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
 * @desc 继承Baidu\Duer\Botsdk\Intercept类，用于测试addIntercept方法
 **/
class TestIntercept extends Baidu\Duer\Botsdk\Intercept{
    /**
     * @param Bot $bot
     * @return mixed
     * 如果返回非null，跳过后面addHandler，addEventListener添加的回调
     **/
    public function preprocess($bot) {
        return [
            'outputSpeech' => 'preprocess',
        ];

    }

    /**
     * @desc 在调用response->build 之前统一对handler的输出结果进行修改
     * @param Bot $bot
     * @param array
     * @return array
     **/
    public function postprocess($bot, $result){
        return $result;
    }
}

/**
 * @desc addIntercept方法测试依赖的Bot
 **/
class AddInterceptMethodTestBot extends Baidu\Duer\Botsdk\Bot{

    /**
     * @param array $postData us对bot的数据。默认可以为空，sdk自行获取
     * @return null
     **/
    public function __construct($postData = []) {
        parent::__construct($postData);

        $this->addIntercept(new TestIntercept());	
    }

}

/**
 * @desc addIntercept方法测试类
 **/
class AddInterceptMethodTest extends PHPUnit_Framework_TestCase{

    /**
     * @before
     */
    public function setupSomeFixtures()
    {
        $data = json_decode(file_get_contents(dirname(__FILE__).'/../json/intent_request.json'), true);
        $this->bot = new AddInterceptMethodTestBot($data);
    }	

    /**
     * @desc 用于addIntercept方法
     **/
    function testAddIntercept(){
        $ret = $this->bot->run();
        $rt = '{"version":"2.0","context":{"intent":{"name":"intentName","score":100,"confirmationStatus":"NONE","slots":{"city":{"name":"city","value":"北京","score":0,"confirmationStatus":"NONE"}}}},"session":{"attributes":{}},"response":{"directives":[],"shouldEndSession":true,"card":null,"resource":null,"outputSpeech":{"type":"PlainText","text":"preprocess"},"reprompt":null}}';
        $this->assertEquals($ret, $rt);	
    }

}

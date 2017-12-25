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
 * @desc IntentRequest类的测试类
 */
require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;

class IntentRequestTest extends PHPUnit_Framework_TestCase{

    /**
     * @before
     */
    public function setupSomeFixtures()
    {
        $this->data = json_decode(file_get_contents(dirname(__FILE__).'/../json/intent_request.json'), true);
        $this->request = new Baidu\Duer\Botsdk\Request($this->data);
    }

    /**
     * @desc 测试getData方法
     */
    function testGetData(){
        $this->assertEquals($this->request->getData(), $this->data);
    }

    /**
     * @desc 测试getNlu方法
     */
    function testGetNlu(){
        $nlu = new Baidu\Duer\Botsdk\Nlu($this->data['request']['intents']);
        $this->assertEquals($this->request->getNlu(), $nlu);
    }

    /**
     * @deprecated   sdk更新后测试 
     * @desc 测试getAudioPlayerContext方法
     */
    function testGetAudioPlayerContext(){

    }

    /**
     * @desc 测试getType方法
     */
    function testGetType(){
        $this->assertEquals($this->request->getType(), 'IntentRequest');
    }

    /**
     * @desc 测试getUserId方法
     */
    function testGetUserId(){
        $this->assertEquals($this->request->getUserId(), 'userId');
    }

    /**
     * @desc 测试getQuery方法
     */
    function testGetQuery(){
        $this->assertEquals($this->request->getQuery(), '所得税查询');
    }

    /**
     * @desc 测试isLaunchRequest方法
     */
    function testIsLaunchRequest(){
        $this->assertFalse($this->request->isLaunchRequest());
    }

    /**
     * @desc 测试isSessionEndRequest方法
     */
    function testIsSessionEndRequest(){
        $this->assertFalse($this->request->isSessionEndRequest());
    }

    /**
     * @desc 测试isSessionEndedRequest方法
     */
    function testIsSessionEndedRequest(){
        $this->assertFalse($this->request->isSessionEndedRequest());
    }

    /**
     * @desc 测试getBotId方法
     */
    function testGetBotId(){
        $this->assertEquals($this->request->getBotId(), 'botId');
    }

    /**
     * @desc 测试isDialogStateCompleted方法
     */
    function testIsDialogStateCompleted(){
        $this->assertFalse($this->request->isDialogStateCompleted());
    }


}

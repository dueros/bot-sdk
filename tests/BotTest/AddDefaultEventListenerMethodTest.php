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
 * @desc AddEventListenerMethodTestBot继承Baidu\Duer\Botsdk\Bot用于测试addEventListener
 **/
class AddDefaultEventListenerMethodTestBot extends Baidu\Duer\Botsdk\Bot{
    /**
     * @param array $postData us对bot的数据。默认可以为空
     * @return null
     **/
    public function __construct($postData = []) {
        parent::__construct($postData);

        $this->addDefaultEventListener('defaultFunc');	
    }

    /**
     * @return array
     **/	
    public function defaultFunc(){
        return [
            'outputSpeech' => '这是一个测试回复，表面bot已经收到了端上返回的event',
        ];
    }
}

class AddDefaultEventListenerMethodTest extends PHPUnit_Framework_TestCase{

    /**
     * @before
     */
    public function setupSomeFixtures()
    {
        $data = json_decode(file_get_contents(dirname(__FILE__).'/../json/audio_player.json'), true);
        $this->bot = new AddDefaultEventListenerMethodTestBot($data);
    }

    /**
     * @desc  用于测试addEventListener方法
     **/	
    function testAddDefaultEventListener(){
        $ret = $this->bot->run();
        $rt = '{"version":"2.0","context":{},"session":{"attributes":{}},"response":{"directives":[],"shouldEndSession":true,"card":null,"resource":null,"outputSpeech":{"type":"PlainText","text":"这是一个测试回复，表面bot已经收到了端上返回的event"},"reprompt":null}}';
        $this->assertEquals($ret, $rt);	
    }

}

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
 * @desc Response类的测试类
 */


require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;

class OutputAudioTest extends PHPUnit_Framework_TestCase{

    /**
     * @before
     */
    public function setupSomeFixtures()
    {
        $this->data = json_decode(file_get_contents(dirname(__FILE__).'/json/intent_request.json'), true);
        $this->request = new Baidu\Duer\Botsdk\Request($this->data);
        $this->session = $this->request->getSession();
        $this->nlu = $this->request->getNlu();
        $this->response = new Baidu\Duer\Botsdk\Response($this->request, $this->session, $this->nlu);	
    }	


    /**
     * @desc 用于测试setPlayBehavior方法
     */
    function testSetPlayBehaviour(){
        $outputAudio = new Baidu\Duer\Botsdk\OutputAudio('PLAY_BEFORE_TTS', 'www.baidu.com');
        $outputAudio->setPlayBehaviour('PLAY_AFTER_TTS');
        $ret = ['outputAudio' => $outputAudio];
        $ret = $this->response->build($ret);;
        $rt = '{"version":"2.0","context":{"intent":{"name":"intentName","score":100,"confirmationStatus":"NONE","slots":{"city":{"name":"city","value":"北京","score":0,"confirmationStatus":"NONE"}}}},"session":{"attributes":{}},"response":{"directives":[],"shouldEndSession":true,"card":null,"resource":null,"outputSpeech":null,"reprompt":null,"outputAudio":{"playBehaviour":"PLAY_AFTER_TTS","audioItems":[{"url":"www.baidu.com"}]}}}';
        $this->assertEquals($ret, $rt);
    }

    /**
     * @desc 用于测试setUrls方法
     */
    function testSetUrls(){
        $outputAudio = new Baidu\Duer\Botsdk\OutputAudio('PLAY_BEFORE_TTS', 'www.baidu.com');
        $outputAudio->setUrls('baidu.com');
        $ret = ['outputAudio' => $outputAudio];
        $ret = $this->response->build($ret);;
        $rt = '{"version":"2.0","context":{"intent":{"name":"intentName","score":100,"confirmationStatus":"NONE","slots":{"city":{"name":"city","value":"北京","score":0,"confirmationStatus":"NONE"}}}},"session":{"attributes":{}},"response":{"directives":[],"shouldEndSession":true,"card":null,"resource":null,"outputSpeech":null,"reprompt":null,"outputAudio":{"playBehaviour":"PLAY_BEFORE_TTS","audioItems":[{"url":"baidu.com"}]}}}';
        $this->assertEquals($ret, $rt);
    }	
}

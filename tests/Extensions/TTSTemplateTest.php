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

class TTSTemplateTest extends PHPUnit_Framework_TestCase{

    /**
     * @before
     */
    public function setupSomeFixtures()
    {
        $this->data = json_decode(file_get_contents(dirname(__FILE__).'/../json/intent_request.json'), true);
        $this->request = new Baidu\Duer\Botsdk\Request($this->data);
        $this->session = $this->request->getSession();
        $this->nlu = $this->request->getNlu();
        $this->response = new Baidu\Duer\Botsdk\Response($this->request, $this->session, $this->nlu);	
    }	


    /**
     * @desc 用于测试addTemplateSlot方法
     */
    function testAddTemplateSlot(){
        $ttsKey = 'ttsKey';
        $slotKey1 = 'slotKey1';
        $slotValue1 = 'slotValue1';
        $slotKey2 = 'slotKey2';
        $slotValue2 = 'slotValue2';
        $ttsTemplate = new Baidu\Duer\Botsdk\Extensions\TTSTemplate($ttsKey);
        $ttsTemplate->addTemplateSlot($slotKey1, $slotValue1);
        $ttsTemplate->addTemplateSlot($slotKey2, $slotValue2);
        $ret = ['outputSpeech' => $ttsTemplate];
        $ret = $this->response->build($ret);;
        $rt = '{"version":"2.0","context":{"intent":{"name":"intentName","score":100,"confirmationStatus":"NONE","slots":{"city":{"name":"city","value":"北京","score":0,"confirmationStatus":"NONE"}}}},"session":{"attributes":{}},"response":{"directives":[],"shouldEndSession":true,"card":null,"resource":null,"outputSpeech":{"type":"TTSTemplate","ttsTemplates":{"ttsKey":"ttsKey","templateSlots":[{"slotKey":"slotKey1","slotValue":"slotValue1"},{"slotKey":"slotKey2","slotValue":"slotValue2"}]}},"reprompt":null}}';
        $this->assertEquals($ret, $rt);
    }

    /**
     * @desc 用于测试setTtsKey方法
     */
    function testSetTtsKey(){
        $ttsKey = 'ttsKey';
        $slotKey1 = 'slotKey1';
        $slotValue1 = 'slotValue1';
        $slotKey2 = 'slotKey2';
        $slotValue2 = 'slotValue2';
        $ttsTemplate =new Baidu\Duer\Botsdk\Extensions\TTSTemplate($ttsKey);
        $ttsTemplate->addTemplateSlot($slotKey1, $slotValue1);
        $ttsTemplate->addTemplateSlot($slotKey2, $slotValue2);
        $ttsTemplate->setTtsKey('ttsKey1');
        $ret = ['outputSpeech' => $ttsTemplate];
        $ret = $this->response->build($ret);;
        $rt = '{"version":"2.0","context":{"intent":{"name":"intentName","score":100,"confirmationStatus":"NONE","slots":{"city":{"name":"city","value":"北京","score":0,"confirmationStatus":"NONE"}}}},"session":{"attributes":{}},"response":{"directives":[],"shouldEndSession":true,"card":null,"resource":null,"outputSpeech":{"type":"TTSTemplate","ttsTemplates":{"ttsKey":"ttsKey1","templateSlots":[{"slotKey":"slotKey1","slotValue":"slotValue1"},{"slotKey":"slotKey2","slotValue":"slotValue2"}]}},"reprompt":null}}';
        $this->assertEquals($ret, $rt);
    }	
}

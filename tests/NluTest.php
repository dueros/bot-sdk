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
 * @desc Nlu类的测试类
 */

require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;

class NluTest extends PHPUnit_Framework_TestCase{

    /**
     * @before
     */
    public function setupSomeFixtures()
    {
        $data = json_decode(file_get_contents(dirname(__FILE__).'/json/intent_request.json'), true);
        $this->nlu = new Baidu\Duer\Botsdk\Nlu($data['request']['intents']);
        $this->data = $data['request']['intents'];
        $this->updateIntent = [
            'name' => $this->nlu->getIntentName(),
            'slots' => $this->data[0]['slots'],
        ];
    }	

    /**
     * @desc 测试getSlot方法
     */
    function testGetSlot(){
        $this->assertEquals($this->nlu->getSlot('city'), '北京');
    }

    /**
     * @desc 测试getSlotConfirmationStatus方法
     */
    function testGetSlotConfirmationStatus(){
        $this->assertEquals($this->nlu->getSlotConfirmationStatus('city'), 'NONE');
    }

    /**
     * @desc 测试getIntentConfirmationStatus方法
     */
    function testGetIntentConfirmationStatus(){
        $this->assertEquals($this->nlu->getIntentConfirmationStatus(), 'NONE');
    }

    /**
     * @desc 测试getIntentName方法
     */
    function testGetIntentName(){
        $this->assertEquals($this->nlu->getIntentName(), 'intentName');
    }

    /**
     * @desc 测试getUpdateIntent方法
     */
    function testGetUpdateIntent(){
        $updateIntent = [
            'name' => $this->nlu->getIntentName(),
            'slots' => $this->data[0]['slots'],
        ];
        $this->assertEquals($this->updateIntent, $updateIntent);
    }

    /**
     * @desc 测试ask方法
     */
    function testAsk(){
        $this->nlu->ask('location');
        $directive = [
            'type' => 'Dialog.ElicitSlot',
            'slotToElicit' => 'location',
            'updatedIntent' => $this->updateIntent,
        ];
        $this->assertEquals($this->nlu->toDirective(), $directive);
    }

    /**
     * @desc 测试setSlot方法
     */
    function testSetSlot(){
        $this->nlu->setSlot('monthsalary', 1212);
        $this->assertEquals($this->nlu->getSlot('monthsalary'), 1212);
    }

    /**
     * @desc 测试setDelegate方法
     */
    function testSetDelegate(){
        $this->nlu->setDelegate();
        $directive = [
            'type' => 'Dialog.Delegate',
            'updatedIntent' => $this->updateIntent,
        ];
        $this->assertEquals($this->nlu->toDirective(), $directive);
    }

    /**
     * @desc 测试setConfirmSlot方法
     */
    function testSetConfirmSlot(){
        $this->nlu->setConfirmSlot('city');
        $directive = [
            'type' => 'Dialog.ConfirmSlot',
            'slotToConfirm' => 'city',
            'updatedIntent' => $this->updateIntent,
        ];
        $this->assertEquals($this->nlu->toDirective(), $directive);
    }

    /**
     * @desc 测试setConfirmIntent方法
     */
    function testSetConfirmIntent(){
        $this->nlu->setConfirmIntent();
        $directive = [
            'type' => 'Dialog.ConfirmIntent',
            'updatedIntent' => $this->updateIntent,
        ];
        $this->assertEquals($this->nlu->toDirective(), $directive);
    }

    /**
     * @desc 测试setAfterSearchScore方法
     */
    function testSetAfterSearchScore(){
        $this->nlu->setAfterSearchScore(10.0);
        $this->assertEquals($this->nlu->getAfterSearchScore(), 10.0);
    }

}

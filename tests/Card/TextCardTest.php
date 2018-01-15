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
 * @desc TextCard类的测试类
 */

require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;

class TextCardTest extends PHPUnit_Framework_TestCase{

    /**
     * @before
     */
    public function setupSomeFixtures()
    {
        $this->card = new Baidu\Duer\Botsdk\Card\TextCard('这是TextCard');
    }	

    /**
     * @desc 测试addCueWords方法
     */
    function testAddCueWords(){
        $this->card->addCueWords(['cuewords1', 'cuewords2']);	
        $card = [
            'type' => 'txt',
            'content' =>  '这是TextCard',
            'cueWords' => ['cuewords1', 'cuewords2'] 
        ];
        $this->assertEquals($this->card->getData(), $card);
    }

    /**
     * @desc 测试setAnchor方法
     */
    function testSetAnchor(){
        $this->card->setAnchor('http://www.baidu.com', '百度');	
            $card = [
                'type' => 'txt',
                'content' =>  '这是TextCard',
                'url' => 'http://www.baidu.com',
                'anchorText' => '百度'
            ];
        $this->assertEquals($this->card->getData(), $card);

    }

    /**
     * @desc 测试getData方法
     */
    function testGetData(){
        $card = [
            'type' => 'txt',
            'content' =>  '这是TextCard',
        ];
        $this->assertEquals($this->card->getData(), $card);
    }

}

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
 * @desc ListCard类测试类
 */

require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;

class ListCardTest extends PHPUnit_Framework_TestCase{

    /**
     * @before
     */
    public function setupSomeFixtures()
    {
        $this->card = new Baidu\Duer\Botsdk\Card\ListCard();
    }	

    /**
     * @desc 测试addItem方法
     */
    function testAddItem(){
        $item = new Baidu\Duer\Botsdk\Card\ListCardItem();
        $item->setTitle('title1');
        $item->setContent('这是ListCardItem1');
        $item->setUrl('http://www.baidu.com');
        $item->setImage('www.png1');

        $item1 = new Baidu\Duer\Botsdk\Card\ListCardItem();
        $item1->setTitle('title2');
        $item1->setContent('这是ListCardItem2');
        $item1->setUrl('http://www.baidu.com');
        $item1->setImage('www.png2');

        $this->card->addItem($item);	
        $this->card->addItem($item1);	
        $card = [
            'type' => 'list',
            'list' =>  [
                [
                    'title' => 'title1', 
                    'content' => '这是ListCardItem1', 
                    'url' => 'http://www.baidu.com', 
                    'image' => 'www.png1'
                ],
                [
                    'title' => 'title2', 
                    'content' => '这是ListCardItem2', 
                    'url' => 'http://www.baidu.com', 
                    'image' => 'www.png2'
                ]
            ]
        ];
        $this->assertEquals($this->card->getData(), $card);
    }

}

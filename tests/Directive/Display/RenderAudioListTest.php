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
 * @desc RenderAudioList类的测试类
 */

require '../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use Baidu\Duer\Botsdk\Directive\Display\RenderAudioList;
use Baidu\Duer\Botsdk\Directive\Display\AudioItem;

class RenderAudioListTest extends PHPUnit_Framework_TestCase{

    /**
     * @before
     */
    public function setupSomeFixtures()
    {
        $this->audioList = new RenderAudioList('audio_list_title');
    }	

    /**
     * @desc 测试getData方法
     */
    function testGetData(){
        $data = array(
            'type' => 'Display.RenderAudioList',
            'token' => 'audio_list_token',
            'title' => 'audio_list_title',
            'behavior' => 'REPLACE',
            'size' => 1,
            'audioItems' => array(
                array(
                    'title' => 'audio_item_title',
                    'titleSubtext1' => 'titleSubtext1',
                    'titleSubtext2' => 'titleSubtext2',
                    'isFavorited' => true,
                    'isMusicVideo' => true,
                    'image' => array(
                        'src' => 'image.png'
                    ),
                    'token' => 'token'
                )
            )
        );
        $this->audioList->setToken('audio_list_token');
        $audioItem = new AudioItem('audio_item_title', 'titleSubtext1');
        $audioItem->setMusicVideoTag(true);
        $audioItem->setFavorited(true);
        $audioItem->setImage('image.png');
        $audioItem->setToken('token');
        $audioItem->setTitleSubtext2('titleSubtext2');
        $this->audioList->addAudioItem($audioItem);
        $this->assertEquals($this->audioList->getData(), $data);
    }

}

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
use Baidu\Duer\Botsdk\Directive\Display\RenderVideoList;
use Baidu\Duer\Botsdk\Directive\Display\VideoItem;

class RenderVideoListTest extends PHPUnit_Framework_TestCase{

    /**
     * @before
     */
    public function setupSomeFixtures()
    {
        $this->videoList = new RenderVideoList('video_list_title');
    }	

    /**
     * @desc 测试getData方法
     */
    function testGetData(){
        $data = array(
            'type' => 'Display.RenderVideoList',
            'token' => 'video_list_token',
            'title' => 'video_list_title',
            'behavior' => 'REPLACE',
            'size' => 1,
            'videoItems' => array(
                array(
                    'title' => 'video_item_title',
                    'titleSubtext1' => 'titleSubtext1',
                    'titleSubtext2' => 'titleSubtext2',
                    'isFavorited' => true,
                    'mediaLengthInMilliseconds' => 10000,
                    'image' => array(
                        'src' => 'image.png'
                    ),
                    'token' => 'token'
                )
            )
        );
        $this->videoList->setToken('video_list_token');
        $videoItem = new VideoItem('video_item_title', 'titleSubtext1');
        $videoItem->setMediaLengthInMilliseconds(10000);
        $videoItem->setFavorited(true);
        $videoItem->setImage('image.png');
        $videoItem->setToken('token');
        $videoItem->setTitleSubtext2('titleSubtext2');
        $this->videoList->addVideoItem($videoItem);
        $this->assertEquals($this->videoList->getData(), $data);
    }

}
